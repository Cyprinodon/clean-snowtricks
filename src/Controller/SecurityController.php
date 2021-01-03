<?php

namespace App\Controller;

use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use App\Services\EmailManager;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/connexion/demander-changement/mot-de-passe", name="password_reset")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param EmailManager $emailManager
     * @return Response
     */
    public function passwordReset_ask(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        EmailManager $emailManager): Response
    {
        $form = $this->createForm(UsernameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $username = $user->getUsername();

            $user = $userRepository->findOneBy(['username' => $username]);

            if($user == null) {
                $this->addFlash('danger', "L'utilisateur demandé n'a pas été trouvé");
                return $this->redirectToRoute('home');
            }

            $token = new CsrfToken($username, $user->getId().uniqid());
            $user->setToken($token);
            $entityManager->persist($user);
            $entityManager->flush();

            try {
                $emailManager->sendPasswordReset($user->getEmail(), $token, $user);
                $successful = true;
            } catch(TransportException $error) {

                $this->addFlash('danger', "Une erreur est survenue lors de l'envoi de votre message. Contactez l'administrateur du site pour résoudre cette issue.");
                $successful = false;
            }

            if($successful)
            {
                $this->addFlash('success', "Votre demande de mot de passe a bien été prise en compte. Si ce nom d'utilisateur existe, un lien a été envoyé à l'adresse e-mail associée.");
            }
            return $this->redirectToRoute('home');
        }
        return $this->render('security/passwordResetAsk.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/connexion/changer/mot-de-passe/{token}", name="password_reset_change")
     * @param Request $request
     * @param string $token
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function passwordReset_change(
        Request $request,
        string $token,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder): Response
    {
/*        if(!$this->isCsrfTokenValid($token->getId(), $token->getValue()))
        {
            return $this->redirectToRoute("link_expired");
        }*/

        $user = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['token' => $token]);

        if($user == null)
        {
            $this->addFlash('danger', "Le jeton ne corresponds pas ou est périmé.");
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form->get('password')->getData()
            ));
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Votre mot de passe a bien été réinitialisé.");
            return $this->redirectToRoute('home');
        }

        return $this->render('security/passwordReset.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
