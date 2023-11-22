<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Models;
use App\Entity\Car; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\ModelsRepository;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\CarRepository;

class CarController extends AbstractController
{
    private $manager;
    private $user;

    public function __construct(EntityManagerInterface $manager, UserRepository $user, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->manager = $manager;
        $this->user = $user;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route('/api_models', name: 'car', methods: ['GET'])]
    public function carModels(Request $request, JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage, UserRepository $user, ModelsRepository $models): Response
    {
        $models = $models->findAll();

        return $this->json($models);
    }

    #[Route('/api/add_car', name: 'app_add_car', methods: ['POST'])]
    public function addCar(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        // Récupérez les données de la requête
        $data = json_decode($request->getContent(), true);

        // Vérifiez si toutes les données nécessaires sont présentes
        if (!isset($data['model_id']) || !isset($data['number_of_places']) || !isset($data['number_of_small_bags']) || !isset($data['number_of_big_bags'])) {
            return new JsonResponse(['message' => 'Les champs requis sont manquants.'], 400);
        }

        // Récupérez le modèle de la base de données
        $modelId = $data['model_id'];
        $model = $entityManager->getRepository(Models::class)->find($modelId);

        if (!$model) {
            return new JsonResponse(['message' => 'Modèle non trouvé.'], 404);
        }

        // Créez une nouvelle voiture
        $car = new Car(); 
        $car->setModels($model);
        $car->setUser($user);
        $car->setNumberOfPlaces($data['number_of_places']);
        $car->setNumberOfSmallBags($data['number_of_small_bags']);
        $car->setNumberOfBigBags($data['number_of_big_bags']);

        // Persistez la voiture en base de données
        $entityManager->persist($car);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Voiture ajoutée avec succès.']);
    }

    #[Route('/api/cars', name: 'app_cars', methods: ['GET'])]
    public function getCars(): JsonResponse
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        // Récupérez les voitures de l'utilisateur
        $cars = $this->getDoctrine()->getRepository(\App\Entity\Car::class)->findBy(['user' => $user]);

        // Transformez les données des voitures en un format adapté pour la réponse JSON
        $formattedCars = [];
        foreach ($cars as $car) {
            $formattedCars[] = [
                'model' => $car->getModels()->getName(), // Vous pouvez ajuster les champs en fonction de votre modèle de données
                'number_of_places' => $car->getNumberOfPlaces(),
                'number_of_small_bags' => $car->getNumberOfSmallBags(),
                'number_of_big_bags' => $car->getNumberOfBigBags(),
            ];
        }

        return new JsonResponse($formattedCars);
    }

    #[Route('/api/mycars', name: 'app_mycars', methods: ['GET'])]
    public function getMyCars(CarRepository $carRepository): JsonResponse
    {
        $user = $this->getUser();
    
        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }
    
        $cars = $carRepository->findBy(['user' => $user], ['models' => 'ASC']);
    
        $formattedCars = [];
        foreach ($cars as $car) {
            $model = $car->getModels();
            $modelName = $model ? $model->getModel() : null;
    
            $formattedCars[] = [
                'car' => [
                    'model' => $modelName,
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
    
        return $this->json($formattedCars);
    }

}
