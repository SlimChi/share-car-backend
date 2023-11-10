<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Modeles;
use App\Entity\Voiture; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\ModelesRepository;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VoitureController extends AbstractController
{
    private $manager;
    private $utilisateur;

    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $utilisateur, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->manager = $manager;
        $this->utilisateur = $utilisateur;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route('/api/modeles', name: 'app_voiture', methods: ['GET'])]
    public function voitureModeles(Request $request, JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage, UtilisateurRepository $utilisateur, ModelesRepository $modeles): Response
    {
        $modeles = $modeles->findAll();

        return $this->json($modeles);
    }

    #[Route('/api/ajouter_voiture', name: 'app_ajouter_voiture', methods: ['POST'])]
    public function ajouterVoiture(Request $request, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        if (!$user instanceof Utilisateur) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        // Récupérez les données de la requête
        $data = json_decode($request->getContent(), true);

        // Vérifiez si toutes les données nécessaires sont présentes
        if (!isset($data['modele_id']) || !isset($data['nbre_de_places']) || !isset($data['nbre_petits_bagages']) || !isset($data['nbre_grands_bagages'])) {
            return new JsonResponse(['message' => 'Les champs requis sont manquants.'], 400);
        }

        // Récupérez le modèle de la base de données
        $modeleId = $data['modele_id'];
        $modele = $entityManager->getRepository(Modeles::class)->find($modeleId);

        if (!$modele) {
            return new JsonResponse(['message' => 'Modèle non trouvé.'], 404);
        }

        // Créez une nouvelle voiture
        $voiture = new Voiture(); 
        $voiture->setModeles($modele);
        $voiture->setUtilisateur($user);
        $voiture->setNbreDePlaces($data['nbre_de_places']);
        $voiture->setNbrePetitsBagages($data['nbre_petits_bagages']);
        $voiture->setNbreGrandsBagages($data['nbre_grands_bagages']);

        // Persistez la voiture en base de données
        $entityManager->persist($voiture);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Voiture ajoutée avec succès.']);
    }

    #[Route('/api/voitures', name: 'app_voitures', methods: ['GET'])]
    public function getVoitures(): JsonResponse
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        if (!$user instanceof Utilisateur) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        // Récupérez les voitures de l'utilisateur
        $voitures = $this->getDoctrine()->getRepository(\App\Entity\Voiture::class)->findBy(['utilisateur' => $user]);

        // Transformez les données des voitures en un format adapté pour la réponse JSON
        $formattedVoitures = [];
        foreach ($voitures as $voiture) {
            $formattedVoitures[] = [
                'modele' => $voiture->getModeles()->getNom(), // Vous pouvez ajuster les champs en fonction de votre modèle de données
                'nbre_de_places' => $voiture->getNbreDePlaces(),
                'nbre_petits_bagages' => $voiture->getNbrePetitsBagages(),
                'nbre_grands_bagages' => $voiture->getNbreGrandsBagages(),
            ];
        }

        return new JsonResponse($formattedVoitures);
    }
}
