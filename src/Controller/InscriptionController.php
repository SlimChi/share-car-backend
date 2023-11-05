<?php

namespace App\Controller;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\CustomMailerService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InscriptionController extends AbstractController
{

    private $manager;
    private $utilisateur;
    private $mailer;

    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $utilisateur, MailerInterface $mailer)
    {
        $this->manager = $manager;
        $this->utilisateur = $utilisateur;
        $this->mailer = $mailer;
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
         

         $confirmationToken = bin2hex(random_bytes(32));

         // Créez un email de confirmation
         $email = (new Email())
         ->from('noreply@example.com')
         ->to($email)
         ->subject('Confirmez votre inscription')
         ->text(
             'Cliquez sur le lien suivant pour confirmer votre inscription : ' .
             $this->generateUrl('app_confirm_inscription', ['token' => $confirmationToken], UrlGeneratorInterface::ABSOLUTE_URL)
         );
 
            // Envoyez l'email en utilisant le service de messagerie
            $this->mailer->send($email);
     
         // Stockez le jeton de confirmation dans la base de données (dans la table Utilisateur)
         $utilisateur->setConfirmationToken($confirmationToken);

         $this->manager->persist($utilisateur);
         $this->manager->flush();  
 
         return new JsonResponse
         (
             [
                 'status'=>true,
                 'message' => 'Inscription effectuée avec succès. Veuillez vérifier votre email pour activer votre compte.'
                 ]
             );
        }
 
     }

     #[Route('/confirm-inscription/{token}', name: 'app_confirm_inscription', methods: ['GET'])]
     public function confirmInscription(string $token, UtilisateurRepository $utilisateurRepository): Response
     {
         $utilisateur = $utilisateurRepository->findOneBy(['confirmationToken' => $token]);
     
         if (!$utilisateur) {
             throw $this->createNotFoundException('Lien de confirmation non valide.');
         }
     
         // Activez le compte de l'utilisateur en supprimant le jeton de confirmation
         $utilisateur->setConfirmationToken(null);
     
         // Activez le compte de l'utilisateur
         $utilisateur->setEnabled(true);
     
         // Enregistrez l'utilisateur mis à jour
         $this->manager->persist($utilisateur);
         $this->manager->flush();
     
         return new JsonResponse([
             'status' => true,
             'message' => 'Inscription confirmée avec succès. Vous pouvez maintenant vous connecter.'
         ]);
     }
     
     

    }

