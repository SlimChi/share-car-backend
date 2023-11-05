<?php

namespace App\Controller;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class ProfilController extends AbstractController
{
    private $manager;
    private $utilisateur;

    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $utilisateur, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
         $this->manager=$manager;
         $this->utilisateur=$utilisateur;
         $this->jwtManager = $jwtManager;
         $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route('/api/profil', name: 'app_profil', methods: ['GET'])]
    public function profil(Request $request, JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage, UtilisateurRepository $utilisateur): Response
    {
        $token = $tokenStorage->getToken();
        $user = $token->getUser();
    
        if (!$user instanceof Utilisateur) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }
    
        return $this->json($user);
    }
    

    #[Route('/api/profil_modif', name: 'app_profil_modif', methods: ['PUT'])]
    public function profilModif(Request $request, UtilisateurRepository $utilisateurRepository, JWTTokenManagerInterface $jwtManager, EntityManagerInterface $manager): Response
    { 
      $data=json_decode($request->getContent(),true);
      
      $adresse=$data['adresse'];
      $code_postal=$data['code_postal'];
      $ville=$data['ville'];
      $date_de_naissance=$data['date_de_naissance'];
;
      $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
      $email = $decodedJwtToken['username'];
      $utilisateur = $utilisateurRepository->findOneByEmail($email);

      if(!$utilisateur)
      {
        return new JsonResponse
        (
            [
              'status'=>false,
              'message'=>'Utilisateur non trouve'

            ]

        );
      }
      if($utilisateur)
       {         
         $utilisateur->setAdresse($adresse);
         $utilisateur->setCodePostal($code_postal);
         $utilisateur->setVille($ville);
         $utilisateur->setDateDeNaissance($date_de_naissance);
       }

       $this->manager->persist($utilisateur);
       $this->manager->flush();

       return new JsonResponse
       (
           [
             'status'=>true,
             'message'=>'Modification effectuée avec succés'
           ]
           );
    }

    #[Route('/api/profil_avatar', name: 'app_profil_avatar', methods: ['PUT'])]
    public function profilModifAvatar(Request $request, UtilisateurRepository $utilisateurRepository, JWTTokenManagerInterface $jwtManager, EntityManagerInterface $manager): Response
    { 
      $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
      $email = $decodedJwtToken['username'];
      $utilisateur = $utilisateurRepository->findOneByEmail($email);

      if(!$utilisateur)
      {
        return new JsonResponse
        (
            [
              'status'=>false,
              'message'=>'Utilisateur non trouve'

            ]

        );
      }
      if($utilisateur)
       {
         $uploadedFile = $request->files->get('avatar');

         if($uploadedFile)
         {
           $fileName = bin2hex(random_bytes(10)).'.'.$uploadedFile->guessExtension();
           $uploadedFile->move("../public/datas",
           $fileName);
           $utilisateur->setAvatar($fileName);
         }
       }
       
       $this->manager->persist($utilisateur);
       $this->manager->flush();

       return new JsonResponse
       (
           [
             'status'=>true,
             'message'=>'Modification effectuée avec succés'
           ]
           );
    }


    #[Route('/api/useraddurl', name: 'user_add_url', methods: ['POST'])]
    public function addImage(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $token = $tokenStorage->getToken();
        $user = $token->getUser();
    
        if (!$user instanceof Utilisateur) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }
    
        $data = json_decode($request->getContent(), true);
    
        if (isset($data['image_url'])) {
            // Créez une nouvelle entité Image, associez-la à l'utilisateur et enregistrez-la en base de données
            $imageUrl = $data['image_url'];
            $image = new Image();
            $image->setUrl($imageUrl);
            $image->setUtilisateur($user);
    
            $entityManager->persist($image);
            $entityManager->flush();
    
            return new JsonResponse(['message' => 'Image ajoutée avec succès.']);
        } else {
            return new JsonResponse(['message' => 'L\'URL de l\'image est manquante.'], 400);
        }
    }

    #[Route('/api/get_user_images', name: 'user_images', methods: ['GET'])]
    public function getUserImages(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $token = $tokenStorage->getToken();
        $user = $token->getUser();
    
        if (!$user instanceof Utilisateur) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }
    
        $images = $user->getImages();
        $imageData = [];
    
        foreach ($images as $image) {
            $imageData[] = [
                'id' => $image->getId(), // Ajoutez l'ID de l'image
                'url' => $image->getUrl(),
            ];
        }
    
        return new JsonResponse(['images' => $imageData]);
    }
    
}
