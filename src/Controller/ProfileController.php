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

}   




//     #[Route('/api/profil/updatepassword', name: 'app_modification_mot_de_passe', methods: ['POST'])]
//     public function modifierMotDePasse(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage): Response
//     {
//         // Récupérez le token JWT de l'user actuel
//         $token = $tokenStorage->getToken();
        
//         if (!$token) {
//             return new JsonResponse(['message' => 'User non authentifié.'], 401);
//         }
    
//         // Vérifiez si le token est valide en essayant de le décoder
//         try {
//             $user = $jwtManager->decode($token);
//         } catch (JWTDecodeFailureException $e) {
//             return new JsonResponse(['message' => 'Jeton invalide.'], 401);
//         }
 
//     $user = $this->getUser();

//     if (!$user instanceof User) {
//         return new JsonResponse(['message' => 'User non authentifié.'], 401);
//     }

//     $data = json_decode($request->getContent(), true);

//     if (!isset($data['ancien_mot_de_passe']) || !isset($data['nouveau_mot_de_passe'])) {
//         return new JsonResponse(['message' => 'Les champs requis sont manquants.'], 400);
//     }


//     $ancienMotDePasse = $data['ancien_mot_de_passe'];
//     if (!$passwordHasher->isPasswordValid($user, $ancienMotDePasse)) {
//         return new JsonResponse(['message' => 'Mot de passe actuel incorrect.'], 400);
//     }

//     $nouveauMotDePasse = $data['nouveau_mot_de_passe'];

//     $hashedPassword = $passwordHasher->hashPassword($user, $nouveauMotDePasse);
//     $user->setMotDePasse($hashedPassword);

//     $entityManager->flush();

//     return new JsonResponse(['message' => 'Mot de passe modifié avec succès.'], 200);
// }

// #[Route('/api/desactiver_profil', name: 'app_profil_desactiver', methods: ['POST'])]
// public function desactiverCompte(Request $request, EntityManagerInterface $entityManager): JsonResponse
// {
//     $user = $this->getUser();

//     if (!$user instanceof User) {
//         return new JsonResponse(['message' => 'User non authentifié.'], 401);
//     }

//     // Ajoutez le code pour désactiver le compte de l'user ici.
//     $user->setEnabled(false);

//     $entityManager->persist($user);
//     $entityManager->flush();

//     return new JsonResponse(['message' => 'Compte désactivé avec succès.']);
// }




// }
