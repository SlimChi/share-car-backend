<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;



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
    public function profil(Request $request, UtilisateurRepository $utilisateurRepository, JWTTokenManagerInterface $jwtManager, EntityManagerInterface $manager): Response
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

      return $this->json($utilisateur);

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
}
