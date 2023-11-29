<?php

namespace App\Services\ServiceImpl;

use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\ModelsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Car;
use App\Entity\Models;
use App\Services\CarServiceInterface;

class CarServiceImpl implements CarServiceInterface
{
    private EntityManagerInterface $entityManager;
    private CarRepository $carRepository;
    private ModelsRepository $modelsRepository;

    public function __construct(EntityManagerInterface $entityManager, CarRepository $carRepository, ModelsRepository $modelsRepository)
    {
        $this->entityManager = $entityManager;
        $this->carRepository = $carRepository;
        $this->modelsRepository = $modelsRepository;
    }

    public function addCar(User $user, Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['model_id']) || !isset($data['number_of_places']) || !isset($data['number_of_small_bags']) || !isset($data['number_of_big_bags'])) {
            return ['status' => false, 'message' => 'Les champs requis sont manquants.'];
        }

        $modelId = $data['model_id'];
        $model = $this->modelsRepository->find($modelId);

        if (!$model) {
            return ['status' => false, 'message' => 'Modèle non trouvé.'];
        }

        $car = new Car();
        $car->setModels($model);
        $car->setUser($user);
        $car->setNumberOfPlaces($data['number_of_places']);
        $car->setNumberOfSmallBags($data['number_of_small_bags']);
        $car->setNumberOfBigBags($data['number_of_big_bags']);

        $this->entityManager->persist($car);
        $this->entityManager->flush();

        return ['status' => true, 'message' => 'Voiture ajoutée avec succès.'];
    }

    public function getCars(User $user): array
    {
        $cars = $this->carRepository->findBy(['user' => $user]);

        $formattedCars = [];
        foreach ($cars as $car) {
            $formattedCars[] = [
                'model' => $car->getModels()->getName(),
                'number_of_places' => $car->getNumberOfPlaces(),
                'number_of_small_bags' => $car->getNumberOfSmallBags(),
                'number_of_big_bags' => $car->getNumberOfBigBags(),
            ];
        }

        return $formattedCars;
    }

    public function getMyCars(User $user): array
    {
        $cars = $this->carRepository->findBy(['user' => $user], ['models' => 'ASC']);

        $formattedCars = [];
        foreach ($cars as $car) {
            $model = $car->getModels();
            $modelName = $model ? $model->getModel() : null;

            $formattedCars[] = [
                'car' => [
                    'model' => $modelName,
                    'id' => $car->getId(),
                    'number_of_places' => $car->getNumberOfPlaces(),
                    'number_of_small_bags' => $car->getNumberOfSmallBags(),
                    'number_of_big_bags' => $car->getNumberOfBigBags(),
                ],
                'model_details' => $model ? [
                    'brand' => $model->getBrand(),
                    'model' => $model->getModel(),
                ] : null,
            ];
        }

        return $formattedCars;
    }
}