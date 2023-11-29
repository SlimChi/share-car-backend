<?php

namespace App\Controller;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use App\Services\ServiceImpl\ProfileServiceImpl;

class ProfileController extends AbstractController
{
    private $profileService;

    public function __construct(ProfileServiceImpl $profileService)
    {
        $this->profileService = $profileService;
    }

    #[Route('/api/get_profile', name: 'app_profile', methods: ['GET'])]
    public function profil(): Response
    {
        return $this->profileService->getProfile();
    }

    #[Route('/api/add_info_profile', name: 'app_profile_modif', methods: ['PUT'])]
    public function addInfoProfil(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        return $this->profileService->addInfoProfile($data);
    }

    #[Route('/api/add_image', name: 'app_add_image', methods: ['POST'])]
    public function addImageProfile(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {
        $data = json_decode($request->getContent(), true);

        return $this->profileService->addImageProfile($data);
    }

    #[Route('/api/get_user_images', name: 'user_images', methods: ['GET'])]
    public function getUserImages(): Response
    {
        return $this->profileService->getUserImages();
    }


    #[Route('/api/profile/updatepassword', name: 'app_updatepassword', methods: ['POST'])]
    public function updatePassword(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        return $this->profileService->updatePassword($data);
    }

    #[Route('/api/disable-account', name: 'app_disableAccount', methods: ['POST'])]
    public function disableAccount(): JsonResponse
    {
        return $this->profileService->disableAccount();
    }

    #[Route('/api/add_biography', name: 'app_add_biography', methods: ['PUT'])]
    public function addBiographyProfile(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        return $this->profileService->addBiographyProfile($data);
    }
}
