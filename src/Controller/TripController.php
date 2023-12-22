<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\TripServiceInterface;
use App\Dto\TripDto;
use App\Repository\TripRepository;
use App\Repository\ImageCarsRepository;
use App\Entity\ImageCars;

class TripController extends AbstractController
{
    private $tripService;

    public function __construct(TripServiceInterface $tripService)
    {
        $this->tripService = $tripService;
    }

    #[Route('/api/add_trip', name: 'app_add_trip', methods: ['POST'])]
    public function addTrip(Request $request): JsonResponse
    {
        return $this->tripService->addTrip($request);
    }

    #[Route('/api/get_all_trips', name: 'app_get_all_trips', methods: ['GET'])]
    public function getAllTrips(): JsonResponse
    {
        return $this->tripService->getAllTrips();
    }

    #[Route('/api/delete_trip', name: 'app_delete_trip', methods: ['POST'])]
    public function deleteTrip(Request $request): JsonResponse
    {
        return $this->tripService->deleteTrip($request);
    }

    #[Route('/api/get_trip_details/{id}', name: 'app_get_trip_details', methods: ['GET'])]
    public function getTripDetails(Request $request, TripRepository $tripRepository, $id, ImageCarsRepository $imageCarsRepository): JsonResponse 
    {       
       

        $trip = $tripRepository->find($id);

        $formattedTrip = [
            'id' => $trip->getId(),
            'price' => $trip->getPrice(),
            'departure_date' => $trip->getDepartureDate(),
            'departure_time' => $trip->getDepartureTime(),
            'car' => [],
           
            'user' => [], 
        
          
            'steps' => [],

            'image_car' => [],
        ];

        $steps = $trip->getSteps();

        foreach ($steps as $step) {
            $formattedTrip['steps'][] = [
                'id' => $step->getId(),
                'departure_address' => $step->getDepartureAddress(),
                'departure_zip_code' => $step->getDepartureZipCode(),
                'departure_city' => $step->getDepartureCity(),
                'arrival_address' => $step->getArrivalAddress(),
                'arrival_zip_code' => $step->getArrivalZipCode(),
                'arrival_city' => $step->getArrivalCity(),
            ];
        }

        $car = $trip->getCar();

     
        $formattedTrip['car'] = [
            'id' => $car->getId(),
            "model" => $car->getModels()->getBrand(),
            "brand" => $car->getModels()->getModel(),
            
        ];

        $tripUser = $trip->getUser();

        $formattedTrip['user'] = [
            
            'username' => $tripUser->getUsernameProfile(),
          

        ];

        $imageCarByTripUser = $imageCarsRepository->findByUser($tripUser);

        foreach ($imageCarByTripUser as $imageCar) {
            $formattedTrip['image_car'][] = [
                'id' => $imageCar->getId(),
                'url' => $imageCar->getImageUrl(),
            ];
        }




 

        return new JsonResponse($formattedTrip);
      
    }


   
}