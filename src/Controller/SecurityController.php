<?php

namespace App\Controller;

use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use App\Services\EmailManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UsernameType;
use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/connexion/demander-changement/mot-de-passe", name="password_reset")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EmailManager $emailManager
     * @return Response
     */
    public function passwordReset_ask(Request $request, UserRepository $userRepository, EmailManager $emailManager): Response
    {
        $form = $this->createForm(UsernameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $username = $user->getUsername();

            $user = $userRepository->findOneBy(['username' => $username]);

            //Vérifier que l'utilisateur existe avant d'envoyer le message
            if($user != null) {
                $token = new CsrfToken($username, $user->getId().uniqid());

                try {
                    $emailManager->sendPasswordReset($user->getEmail(), $token, $user);
                } catch(TransportException $error) {
                    $this->addFlash('danger', "SendGrid refuse encore et toujours d'envoyer ces damnés messages : ".$error->getMessage());
                }
            }
            $this->addFlash('success', "Votre demande de mot de passe a bien été prise en compte. Si ce nom d'utilisateur existe, un lien a été envoyé à l'adresse e-mail associée.");
            return $this->redirectToRoute('home');
        }
        return $this->render('security/passwordResetAsk.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/connexion/changer/mot-de-passe/{token}", name="password_reset_change")
     * @param Request $request
     * @param CsrfToken $token
     * @param EntityManagerInterface $entityManager
     * @param PasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function passwordReset_change(
        Request $request,
        CsrfToken $token,
        EntityManagerInterface $entityManager,
        PasswordEncoderInterface $passwordEncoder): Response
    {
        if(!$this->isCsrfTokenValid($token->getId(), $token->getValue()))
        {
            return $this->redirectToRoute("link_expired");
        }

        $user = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => $token->getId()]);

        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $form->getData();
            $user->setPassword($passwordEncoder->encodePassword($password, $user->getSalt()));
            $entityManager->flush();

            $this->addFlash('success', "Votre mot de passe a bien été réinitialisé.");
            $this->redirectToRoute('home');
        }

        return $this->render('security/passwordReset.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
