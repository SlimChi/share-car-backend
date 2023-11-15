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
     
        $data = json_decode($request->getContent(), true);

        if (isset($data['image_url'])) {
         
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
