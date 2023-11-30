<?php

namespace App\Controller;

use App\Entity\Models;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ModelsRepository;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\CarRepository;
use App\Services\CarServiceInterface;

class CarController extends AbstractController
{
    private $manager;
 
    private CarServiceInterface $carService;

    public function __construct(EntityManagerInterface $manager,
    
        TokenStorageInterface $tokenStorageInterface,
        JWTTokenManagerInterface $jwtManager,
        CarServiceInterface $carService)
    {
        $this->manager = $manager;
     
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->carService = $carService;

    }

    #[Route('/api_models', name: 'car', methods: ['GET'])]
    public function carModels(Request $request, JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage,  ModelsRepository $models): Response
    {
        $models = $models->findAll();

        return $this->json($models);
    }

    #[Route('/api/add_car', name: 'app_add_car', methods: ['POST'])]
    public function addCar(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        $result = $this->carService->addCar($user, $request);

        if ($result['status']) {
            return new JsonResponse(['message' => $result['message']]);
        } else {
            return new JsonResponse(['error' => $result['message']], 400);
        }
    }

    #[Route('/api/cars', name: 'app_cars', methods: ['GET'])]
    public function getCars(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        $cars = $this->carService->getCars($user);

        return new JsonResponse($cars);
    }

    #[Route('/api/mycars', name: 'app_mycars', methods: ['GET'])]
    public function getMyCars(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        $cars = $this->carService->getMyCars($user);

        return $this->json($cars);
    }

    
}

