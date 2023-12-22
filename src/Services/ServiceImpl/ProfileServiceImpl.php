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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ProfileServiceImpl
{
    private $jwtManager;
    private $tokenStorageInterface;
    private $manager;
    private $validationService;
    private $passwordHasher;
    private $entityManager;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        TokenStorageInterface $tokenStorageInterface, 
        EntityManagerInterface $entityManager,
        ValidationService $validationService,
        UserPasswordHasherInterface $passwordHasher,
        
    )
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->entityManager = $entityManager;
        $this->validationService = $validationService;
        $this->passwordHasher = $passwordHasher;
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

    $usernameExist = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $data['username']]);

    if ($usernameExist && $user->getUsernameProfile() !== $data['username']) {
        return new JsonResponse(['message' => 'Ce pseudo est déjà utilisé.'], 400);
    }

    $emailExist = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

    if ($emailExist && $user->getEmail() !== $data['email']) {
        return new JsonResponse(['message' => 'Cet email est déjà utilisé.'], 400);
    }

    $validationResult = $this->validationService->validateDataProfile($data);

    if (!$validationResult['status']) {
        return new JsonResponse(['message' => 'Validation failed', 'errors' => $validationResult['message']], 400);
    }

    // Modifier l'entité User uniquement si la validation réussit
    $user->setFirstName($data['firstName']);
    $user->setLastName($data['lastName']);
    $user->setUsername($data['username']);
    $user->setAddress($data['address']);
    $user->setZipCode($data['zipCode']);
    $user->setCity($data['city']);
    $user->setDateOfBirth($data['dateOfBirth']);
    // $user->setBiography($data['biography']);

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return new JsonResponse($this->serializeUser($user));
}
        
        public function addImageProfile(array $data): JsonResponse
    {
        $token = $this->tokenStorageInterface->getToken();
        $user = $token->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User non authentifié.'], 401);
        }

        $imageUrl = $data['imageUrl'];
        $image= new Image();
        $image->setUrl($imageUrl);
        $image->setUser($user);

        $this->entityManager->persist($image);
        $this->entityManager->flush();

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

    
        public function updatePassword(array $data): JsonResponse
        {
            $user = $this->getUserFromToken();

            if (!$user instanceof User) {
                return new JsonResponse(['message' => 'User non authentifié.'], 401);
            }

            if (!isset($data['old_password']) || !isset($data['new_password'])) {
                return new JsonResponse(['message' => 'Les champs requis sont manquants.'], 400);
            }

            $oldPassword = $data['old_password'];

            if (!$this->isOldPasswordValid($user, $oldPassword)) {
                return new JsonResponse(['message' => 'Mot de passe actuel incorrect.'], 400);
            }

            $newPassword = $data['new_password'];

            $this->updateUserPassword($user, $newPassword);

            return new JsonResponse(['message' => 'Mot de passe modifié avec succès.'], 200);
        }

        public function disableAccount(): JsonResponse
        {
            $user = $this->getUserFromToken();

            if (!$user instanceof User) {
                return new JsonResponse(['message' => 'User non authentifié.'], 401);
            }

            $this->disableUserAccount($user);

            return new JsonResponse(['message' => 'Compte désactivé avec succès.']);
        }

        private function getUserFromToken(): ?User
        {
            $token = $this->tokenStorageInterface->getToken();
        
            if (!$token) {
                return null;
            }
        
            try {
                return $this->tokenStorageInterface->getToken()->getUser();
            } catch (JWTDecodeFailureException $e) {
                return null;
            }
        }
        

        private function isOldPasswordValid(User $user, string $oldPassword): bool
        {
            return $this->passwordHasher->isPasswordValid($user, $oldPassword);
        }

        private function updateUserPassword(User $user, string $newPassword): void
        {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);
            $this->entityManager->flush();
        }

        private function disableUserAccount(User $user): void
        {
            $user->setEnabled(false);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        public function addBiographyProfile(array $data): JsonResponse
        {
            $token = $this->tokenStorageInterface->getToken();
            $user = $token->getUser();
    
            if (!$user instanceof User) {
                return new JsonResponse(['message' => 'User non authentifié.'], 401);
            }
    
            $user->setBiography($data['biography']);
    
            $this->entityManager->persist($user);
            $this->entityManager->flush();
    
            return new JsonResponse($this->serializeUser($user));
        }
}
