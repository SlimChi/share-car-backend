<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use App\Service\CustomMailerService;

class InscriptionController extends AbstractController
{

    private $manager;
    private $utilisateur;
   
    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $utilisateur)
    {
         $this->manager=$manager;
         $this->utilisateur=$utilisateur;
    }

    #[Route('/inscription', name: 'app_inscription', methods: ['POST'])]
    public function inscription(Request $request): Response
    {
        $data=json_decode($request->getContent(),true);

        $nom =$data['nom'];
        $prenom=$data['prenom'];
        $pseudo=$data['pseudo'];
        $email=$data['email'];
        $mot_de_passe=$data['mot_de_passe'];
        $date_inscription=new \DateTime();
 
        $email_exist=$this->utilisateur->findOneByEmail($email);

        if($email_exist)
        {
           return new JsonResponse
           (
               [
                 'status'=>false,
                 'message'=>'Cet email existe déjà, veuillez le changer'
               ]
 
               );
        }
 
        else 
 
        {

         $hashPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        
         $utilisateur = new Utilisateur();
 
         $utilisateur->setNom($nom);
         $utilisateur->setPrenom($prenom);
         $utilisateur->setPseudo($pseudo);
         $utilisateur->setDateInscription($date_inscription);
         $utilisateur->setCreditJeton(50);
         $utilisateur->setEmail($email);
         $utilisateur->setMotDePasse($hashPassword);
         $utilisateur->setRoles(['ROLE_USER']);
         
         $this->manager->persist($utilisateur);
         $this->manager->flush();  
 
         return new JsonResponse
         (
             [
                 'status'=>true,
                 'message'=>'Inscription effectuée avec succès'
             ]
             );
        }
 
     }
    }

