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
use Symfony\Component\Mime\Address;
use Twig\Environment;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InscriptionController extends AbstractController
{

    private $manager;
    private $utilisateur;
    private $mailer;

    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $utilisateur, MailerInterface $mailer, Environment $twig)
    {
        $this->manager = $manager;
        $this->utilisateur = $utilisateur;
        $this->mailer = $mailer;
        $this->twig = $twig; 
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
         ->from(new Address('noreply@example.com'))
         ->to($email)
         ->subject('Confirmez votre inscription')
         ->html(
             $this->twig->render('emails/confirmation_email.html.twig', [
                 'confirmationUrl' => $this->generateUrl('app_confirm_inscription', ['token' => $confirmationToken], UrlGeneratorInterface::ABSOLUTE_URL)
             ])
         );
 
            // Envoyez l'email en utilisant le service de messagerie
            $this->mailer->send($email);
     
         // Stockez le jeton de confirmation dans la base de données (dans la table Utilisateur)
         $utilisateur->setConfirmationToken($confirmationToken);
         $utilisateur->setEnabled(false);
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
             'message' => 'Email confirmer. Vous pouvez maintenant vous connecter.'
         ]);
     }
     
     #[Route('/reinitialisationmdp', name: 'reinitialisation-mdp', methods: ['POST'])]
    public function demandeReinitialisationMotDePasse(Request $request, UtilisateurRepository $utilisateurRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];

        // Recherchez l'utilisateur par son adresse email
        $utilisateur = $utilisateurRepository->findOneBy(['email' => $email]);

        if (!$utilisateur) {
            return new JsonResponse(['status' => false, 'message' => 'Aucun utilisateur trouvé avec cette adresse email.']);
        }

        // Générez un jeton de réinitialisation de mot de passe
        $token = bin2hex(random_bytes(32));

        // Enregistrez le jeton de réinitialisation de mot de passe dans la base de données (dans la table Utilisateur)
        $utilisateur->setResetPasswordToken($token);
        $this->manager->flush();

        // Envoi de l'e-mail de réinitialisation
        $email = (new Email())
            ->from('noreply@example.com')
            ->to($email)
            ->subject('Réinitialisation de mot de passe')
            ->html(
                $this->twig->render('resetPasswordEmail/resetpassword.html.twig', [
                    'resetPasswordUrl' => $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL),
                    'firstName' => $utilisateur->getNom(),
                    'lastName' => $utilisateur->getPrenom(),
                ])
            );

        $this->mailer->send($email);

        return new JsonResponse([
            'status' => true,
            'message' => 'Un e-mail de réinitialisation de mot de passe a été envoyé avec succès.',
            'resetPasswordToken' => $token,
        ]);
    }

    #[Route('/resetpassword/{token}', name: 'reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, string $token, UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $password = $data['password'];

        // Recherchez l'utilisateur par le jeton de réinitialisation de mot de passe
        $utilisateur = $utilisateurRepository->findOneBy(['resetPasswordToken' => $token]);

        if (!$utilisateur) {
            return new JsonResponse(['status' => false, 'message' => 'Jeton de réinitialisation de mot de passe invalide.']);
        }

        // Réinitialisez le mot de passe de l'utilisateur
        $hashedPassword = $passwordHasher->hashPassword($utilisateur, $password);
        $utilisateur->setMotDePasse($hashedPassword);
        $utilisateur->setResetPasswordToken(null);

        $this->manager->flush();

        return new JsonResponse([
            'status' => true,
            'message' => 'Mot de passe réinitialisé avec succès.',
        ]);
    }

    }

