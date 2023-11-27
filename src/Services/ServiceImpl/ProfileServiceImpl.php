<?php


namespace App\Services\ServiceImpl;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;
use App\Services\Validator\ValidationService; 
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

// use App\Services\ValidationService;



class ProfileServiceImpl
{
    private $jwtManager;
    private $tokenStorageInterface;
    private $manager;
    private $validationService;
    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        TokenStorageInterface $tokenStorageInterface, 
        EntityManagerInterface $manager,
        ValidationService $validationService,
      )
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->manager = $manager;
        $this->validationService = $validationService;
    }
    public function getProfile(): Response
    {
        $token = $this->tokenStorageInterface->getToken();
        $user = $token->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User non authentifié.'], 401);
        }

        return new JsonResponse($this->serializeUser($user));
    }


    private function serializeUser(User $user): array
    {

        return [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt() ? $user->getCreatedAt()->format('Y-m-d H:i:s') : null,
            'username' => $user->getUsernameProfile(),
            'creditCoin' => $user->getCreditCoin(),
            'roles' => $user->getRoles(),
            'address' => $user->getAddress(),
            'zipCode' => $user->getZipCode(),
            'city' => $user->getCity(),
            'dateOfBirth' => $user->getDateOfBirth(),
            'biography' => $user->getBiography(),

        ];
    }

    public function addInfoProfile(array $data): JsonResponse
    {
        $token = $this->tokenStorageInterface->getToken();
        $user = $token->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User non authentifié.'], 401);
        }

        $validationResult = $this->validationService->validateDataProfile($data);

        if (!$validationResult['status']) {
            return new JsonResponse(['message' => 'Validation failed', 'errors' => $validationResult['message']], 400);
        }

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setAddress($data['address']);
        $user->setZipCode($data['zipCode']);
        $user->setCity($data['city']);
        $user->setDateOfBirth($data['dateOfBirth']);
        // $user->setBiography($data['biography']);

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse($this->serializeUser($user));
    }
        
        public function addImageProfile(array $data): JsonResponse
    {
        $token = $this->tokenStorageInterface->getToken();
        $user = $token->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User non authentifié.'], 401);
        }

        $imageUrl = $data['image_url'];
        $image= new Image();
        $image->setUrl($imageUrl);
        $image->setUser($user);

        $this->manager->persist($image);
        $this->manager->flush();

        return new JsonResponse($this->serializeUser($user));

    }

    public function getUserImages(): Response
    {
        $token = $this->tokenStorageInterface->getToken();
        $user = $token->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User non authentifié.'], 401);
        }

        $images = $user->getImages();
        $imageData = [];

        foreach ($images as $image) {
            $imageData[] = [
                'id' => $image->getId(),
                'url' => $image->getUrl(),
            ];
        }

        return new JsonResponse(['images' => $imageData]);
    }

    public function addBiographyProfile(array $data): JsonResponse
    {
        $token = $this->tokenStorageInterface->getToken();
        $user = $token->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User non authentifié.'], 401);
        }

        $user->setBiography($data['biography']);

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse($this->serializeUser($user));
    }

    

    

}