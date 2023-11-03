<?php

namespace App\Controller;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController

{
    #[Route('/upload-image', name: 'upload_image', methods: ['POST'])]
    public function uploadImage(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérez les données de l'image depuis la requête
        $data = json_decode($request->getContent(), true);

        // Vérifiez si l'URL de l'image est présente dans les données
        if (isset($data['image_url'])) {
            // Créez une nouvelle entité Image et enregistrez l'URL de l'image en base de données
            $image = new Image();
            $image->setUrl($data['image_url']);

            $entityManager->persist($image);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Image téléchargée avec succès.']);
        } else {
            return new JsonResponse(['message' => 'L\'URL de l\'image est manquante.'], 400);
        }
    }
}
