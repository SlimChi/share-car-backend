<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Com;
use App\Entity\Trip;
use App\Entity\User;
use App\Repository\ComRepository;
use App\Repository\UserRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\JsonResponse;

class ComController extends AbstractController
{
    private ComRepository $comRepository;

    public function __construct(EntityManagerInterface $entityManager, ComRepository $comRepository)
    {
        $this->comRepository = $comRepository;
        $this ->entityManager = $entityManager;

    }
    #[Route('/api/add_com', name: 'app_add_com', methods: ['POST'])]
    public function addCom(Request $request, EntityManagerInterface $entityManager, Trip $trip): Response
    {
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);

        $tripId = $data['trip_id'];
        $note_com = $data['note_com'];
        $add_com = $data['com'];
        $date_com = new \DateTime('now');

        $date_com->format('d-m-Y');
        

        $trip = $this->entityManager->getRepository(Trip::class)->find($tripId);

        $com = new Com();

        $com->setNoteCom($note_com);
        $com->setCom($add_com);
        $com->setUser($user);
        $com->setTrip($trip);
        $com->setDateCom($date_com);

        $this ->entityManager->persist($com);
        $this ->entityManager->flush();

        return $this->json([
            'status' => 200,
            'message' => 'Commentaire ajouté',
        ]);
    }

    #[Route('/api/get_coms_by_trip/{id}', name: 'app_get_coms_by_trip', methods: ['GET'])]
    public function getComsByTrip(Request $request, EntityManagerInterface $entityManager, ComRepository $comRepository): Response
    {
       
        $tripId = $request->get('id');

        $trip = $this->entityManager->getRepository(Trip::class)->find($tripId);

        $comsByTrip = $comRepository->findByTrip($trip);

        $coms = [];

        foreach ($comsByTrip as $com) {
        
            $coms[] = [
         
           
                'note_com' => $com->getNoteCom(),
                'com' => $com->getCom(),
                'date_com' => $com->getDateCom(),
                'username' => $com->getUser()->getUsernameProfile(),
            ];
        }

        return new JsonResponse(
            $coms
        );
    }

}