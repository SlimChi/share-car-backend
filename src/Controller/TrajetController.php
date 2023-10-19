<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Trajet;
use App\Repository\UtilisateurRepository;
use App\Repository\TrajetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class TrajetController extends AbstractController
{
    private $manager;
    private $utilisateur;

    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $utilisateur, TrajetRepository $trajet)
    {
         $this->manager=$manager;

         $this->utilisateur=$utilisateur;
    }

    #[Route('/trajet', name: 'app_trajet', methods: ['POST'])]
    public function index(Request $request, UtilisateurRepository $utilisateur, ConnexionController $connexion): Response
    {
        $data=json_decode($request->getContent(),true);

        $prix=$data['prix'];
        $fumeur=$data['fumeur'];
        $silence=$data['silence'];
        $musique=$data['musique'];
        $animaux=$data['animaux'];
        $date_depart=new \DateTime('@'.strtotime('now'));;
        $heure_depart=new \DateTime('@'.strtotime('now'));;

        $trajet = new Trajet();

        $trajet->setPrix($prix);
        $trajet->setFumeur($fumeur);
        $trajet->setSilence($silence);
        $trajet->setMusique($musique);
        $trajet->setAnimaux($animaux);
        $trajet->setDateDepart($date_depart);
        $trajet->setHeureDepart($heure_depart);
        $trajet->setUtilisateur($this->utilisateur->findOneById(1));
        
            var_dump($trajet);

        $this->manager->persist($trajet);
        $this->manager->flush();

        return new JsonResponse(['status'=>true]);
    }
}
