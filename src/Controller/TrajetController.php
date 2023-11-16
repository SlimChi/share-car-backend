<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Voiture; 
use App\Entity\Trajet;
use App\Entity\Etape;
use App\Repository\VoitureRepository;
use App\Repository\TrajetRepository;
use App\Repository\EtapeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



class TrajetController extends AbstractController
{
    private $manager;
    private $utilisateur;

    public function __construct(EntitymanagerInterface $manager, UtilisateurRepository $utilisateur, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->manager = $manager;
        $this->utilisateur = $utilisateur;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route('/api/ajouter_trajet', name: 'app_ajouter_trajet', methods: ['POST'])]
    public function ajouterTrajet(Request $request, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager, ): JsonResponse
    {
      
        $user = $this->getUser();

        if (!$user instanceof Utilisateur) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

   
        $data = json_decode($request->getContent(), true);

    
        if (!isset($data['voiture_id']) || !isset($data['prix']) || !isset($data['fumeur']) || !isset($data['silence']) || !isset($data['musique']) || !isset($data['animaux']) || !isset($data['date_depart']) || !isset($data['heure_depart'])) {
            return new JsonResponse(['message' => 'Les champs requis sont manquants.'], 400);
        }

        $voitureId = $data['voiture_id'];
        $voiture = $entityManager->getRepository(Voiture::class)->find($voitureId);

        if (!$voiture) {
            return new JsonResponse(['message' => 'Voiture non trouvable.'], 404);
        }

  
        $trajet = new Trajet();
        $trajet->setVoiture($voiture);
        $trajet->setUtilisateur($user);
        $trajet->setPrix($data['prix']);
        $trajet->setFumeur($data['fumeur']);
        $trajet->setSilence($data['silence']);
        $trajet->setMusique($data['musique']);
        $trajet->setAnimaux($data['animaux']);
        $trajet->setDateDepart($data['date_depart']);
        $trajet->setHeureDepart($data['heure_depart']);


        $entityManager->persist($trajet);
        $entityManager->flush();

        $data = json_decode($request->getContent(), true);

        $adresse_depart = $data['adresse_depart'];
        $code_postal_depart = $data['code_postal_depart'];
        $ville_depart = $data['ville_depart'];
        $adresse_arrivee = $data['adresse_arrivee'];
        $code_postal_arrivee = $data['code_postal_arrivee'];
        $ville_arrivee = $data['ville_arrivee'];
       
        $etape = new Etape();
        $etape->setTrajet($trajet);
        $etape->setAdresseDepart($adresse_depart);
        $etape->setCodePostalDepart($code_postal_depart);
        $etape->setVilleDepart($ville_depart);
        $etape->setAdresseArrivee($adresse_arrivee);
        $etape->setCodePostalArrivee($code_postal_arrivee);
        $etape->setVilleArrivee($ville_arrivee);

        $entityManager->persist($etape);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Etape ajoutée avec succés.']);
   
    }

 

    #[Route('/api/get_all_trajets', name: 'app_get_all_trajets', methods: ['GET'])]
    public function getAllTrajets(EntityManagerInterface $entityManager): JsonResponse
    {

        $trajets = $entityManager->getRepository(Trajet::class)->findAll();

        $formattedTrajets = [];

        foreach ($trajets as $trajet) {
            $formattedTrajets[] = [
                'id' => $trajet->getId(),
                'prix' => $trajet->getPrix(),
                'fumeur' => $trajet->isFumeur(),
                'silence' => $trajet->isSilence(),
                'musique' => $trajet->isMusique(),
                'animaux' => $trajet->isAnimaux(),
                'date_depart' => $trajet->getDateDepart(),
                'heure_depart' => $trajet->getHeureDepart(),
                'voiture' => [
                    'id' => $trajet->getVoiture()->getId(),
                    // Ajoutez d'autres propriétés de la voiture si nécessaire
                ],
                'utilisateur' => [
                    'id' => $trajet->getUtilisateur()->getId(),
                    // Ajoutez d'autres propriétés de l'utilisateur si nécessaire
                ],
                'etapes' => [],
            ];

            $etapes = $trajet->getEtapes();

            foreach ($etapes as $etape) {
                $formattedTrajets[count($formattedTrajets) - 1]['etapes'][] = [
                    'id' => $etape->getId(),
                    'adresse_depart' => $etape->getAdresseDepart(),
                    'code_postal_depart' => $etape->getCodePostalDepart(),
                    'ville_depart' => $etape->getVilleDepart(),
                    'adresse_arrivee' => $etape->getAdresseArrivee(),
                    'code_postal_arrivee' => $etape->getCodePostalArrivee(),
                    'ville_arrivee' => $etape->getVilleArrivee(),
                ];
            }
        }

        return new JsonResponse($formattedTrajets);
    }

    #[Route('/api/supprimer_trajet', name: 'app_supprimer_trajet', methods: ['POST'])]
    public function supprimerTrajet(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, UtilisateurRepository $utilisateur): JsonResponse
    {
        $token = $tokenStorage->getToken();
        $user = $token->getUser();

        if (!$user instanceof Utilisateur) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        $trajets = $this->manager->getRepository(Trajet::class)->findBy(['utilisateur' => $user]);
       

        $data = json_decode($request->getContent(), true);

        $trajetId = $data['trajet_id'];

        $trajet = $entityManager->getRepository(Trajet::class)->find($trajetId);

        $etape = $entityManager->getRepository(Etape::class)->findOneBy(['trajet' => $trajet]);

        if (!$trajet) {
            return new JsonResponse(['message' => 'Trajet non trouvable.'], 404);
        }

        if (!$etape) {
            
            $entityManager->remove($trajet);
            $entityManager->flush();
        }

        if ($etape) {
            
            $entityManager->remove($etape);
            $entityManager->flush();
        }

        $entityManager->remove($trajet);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Trajet supprimé avec succès.']);

    }
    
}


  

       

