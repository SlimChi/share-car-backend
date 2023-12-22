<?php

namespace App\Services\ServiceImpl;

use App\Entity\User;
use App\Entity\Car;
use App\Entity\Trip;
use App\Entity\Step;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Services\TripServiceInterface;

/**
 * @Service
 */
class TripServiceImpl implements TripServiceInterface
{
    private $entityManager;
    private $userRepository;
    private $tokenStorage;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function addTrip(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['car_id']) || !isset($data['price']) || !isset($data['smoker']) || !isset($data['silence']) || !isset($data['music']) || !isset($data['pets']) || !isset($data['departure_date']) || !isset($data['departure_time'])) {
            return new JsonResponse(['message' => 'Les champs requis sont manquants.'], 400);
        }

        $carId = $data['car_id'];
        $car = $this->entityManager->getRepository(Car::class)->find($carId);

        if (!$car) {
            return new JsonResponse(['message' => 'Voiture non trouvée.'], 404);
        }

        $trip = new Trip();
        $trip->setCar($car);
        $trip->setUser($user);
        $trip->setPrice($data['price']);
        $trip->setSmoker($data['smoker']);
        $trip->setSilence($data['silence']);
        $trip->setMusic($data['music']);
        $trip->setPets($data['pets']);
        $trip->setDepartureDate($data['departure_date']);
        $trip->setDepartureTime($data['departure_time']);

        $this->entityManager->persist($trip);
        $this->entityManager->flush();

        $departureAddress = $data['departure_address'];
        $departureZipCode = $data['departure_zip_code'];
        $departureCity = $data['departure_city'];
        $arrivalAddress = $data['arrival_address'];
        $arrivalZipCode = $data['arrival_zip_code'];
        $arrivalCity = $data['arrival_city'];

        $step = new Step();
        $step->setTrip($trip);
        $step->setDepartureAddress($departureAddress);
        $step->setDepartureZipCode($departureZipCode);
        $step->setDepartureCity($departureCity);
        $step->setArrivalAddress($arrivalAddress);
        $step->setArrivalZipCode($arrivalZipCode);
        $step->setArrivalCity($arrivalCity);

        $this->entityManager->persist($step);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Etape ajoutée avec succès.']);
    }

    public function getAllTrips(): JsonResponse
    {
        

        $trips = $this->entityManager->getRepository(Trip::class)->findAll();

        $formattedTrips = [];

        foreach ($trips as $trip) {
            $formattedTrips[] = $this->formatTrip($trip);
        }

        return new JsonResponse($formattedTrips);
    }

    private function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();
        return $token->getUser();
    }
    private function formatTrip(Trip $trip): array
    {
        $formattedTrip = [
            'id' => $trip->getId(),
            'price' => $trip->getPrice(),
            'smoker' => $trip->isSmoker(),
            'silence' => $trip->isSilence(),
            'music' => $trip->isMusic(),
            'pets' => $trip->isPets(),
            'departure_date' => $trip->getDepartureDate(),
            'departure_time' => $trip->getDepartureTime(),
            'car' => [
                'id' => $trip->getCar()->getId(),
                // Ajoutez d'autres propriétés de la voiture si nécessaire
            ],
            'user' => [
                'id' => $trip->getUser()->getId(),
                'username' => $trip->getUser()->getUsernameProfile(),
                'image' => $this->getUserImage($trip->getUser()),
                // 'images' => $trip->getUser()->$Images->getUrl(),
                // Ajoutez d'autres propriétés de l'utilisateur si nécessaire
            ],
            'steps' => [],
        ];
    
     
            $steps = $trip->getSteps();
 
    
        foreach ($steps as $step ) {
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


        
    
        return $formattedTrip;
    }
    

    public function deleteTrip(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        $data = json_decode($request->getContent(), true);

        $tripId = $data['trip_id'];

        $trip = $this->entityManager->getRepository(Trip::class)->find($tripId);

        if (!$trip) {
            return new JsonResponse(['message' => 'Trajet non trouvé.'], 404);
        }

        $step = $this->entityManager->getRepository(Step::class)->findOneBy(['trip' => $trip]);

        if (!$step) {
            $this->entityManager->remove($trip);
            $this->entityManager->flush();
        } else {
            $this->entityManager->remove($step);
            $this->entityManager->remove($trip);
            $this->entityManager->flush();
        }

        return new JsonResponse(['message' => 'Trajet supprimé avec succès.']);
    }

    private function getUserImage(User $user): ?string
{
    $images = $user->getImages();

    // Vérifiez si l'utilisateur a des images
   return $images->count() > 0 ? $images->first()->getUrl() : null;

 
} 
  
// public function getTripDetails(Request $request): JsonResponse
// {

//     $data = json_decode($request->getContent(), true);
    
//  return new JsonResponse($data['id']);

//     if (!$tripId) {
//         return new JsonResponse(['message' => 'Veuillez fournir un identifiant.'], 400);
//     }

//     $trip = $this->entityManager->getRepository(Trip::class)->find($tripId);

//     if (!$trip) {
//         return new JsonResponse(['message' => 'Trajet non trouvé.'], 404);
//     }

//     $formattedTrips = [];

//         foreach ($trips as $trip) {
//             $formattedTrips[] = $this->formatTrip($trip);
//         }

//         return new JsonResponse($formattedTrips);
// }
}