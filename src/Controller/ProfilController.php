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

class ProfilController extends AbstractController
{
    private $manager;
    private $utilisateur;

    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $utilisateur)
    {
         $this->manager=$manager;
         $this->utilisateur=$utilisateur;
    }

    #[Route('/profil', name: 'app_profil', methods: ['POST', 'GET', 'PUT'])]
    public function profil(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
       
       $data=json_decode($request->getContent(),true);
       
       $id=$data['id'];
       $pseudo=$data['pseudo'];
       $adresse=$data['adresse'];
       $code_postal=$data['code_postal'];
       $ville=$data['ville'];
       $date_de_naissance=$data['date_de_naissance'];

       $utilisateur = $utilisateurRepository->findOneById($id);

       if($utilisateur)
       {
         $utilisateur->setPseudo($pseudo);
        
         $uploadedFile = $request->files->get('avatar');

         if (!$uploadedFile instanceof UploadedFile) {
             return new JsonResponse
             (
                 [
                   'status'=>false,
                   'message'=>'Fichier non valide'

                 ]
             )
             ;
         }
        

         $fileName = bin2hex(random_bytes(10)).'.'.$uploadedFile->guessExtension();
         $uploadedFile->move("../public/datas",
         $fileName);
         $utilisateur->setAvatar($fileName);
         
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
}
