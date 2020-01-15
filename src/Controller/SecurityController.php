<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Token;
use App\Form\RegistrationType;
use App\Repository\TokenRepository;
use App\Services\TokenSendler;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     *@Route("/registration", name="registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, UserPasswordEncoder $passwordEncoder, TokenSendler $tokenSendler )
    {
        $user = new User;
        $form = $this->createForm(RegistrationType::class, $user);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()){

            $passwordEncoded = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordEncoded);
            $user->setRoles(['ROLE_ADMIN']);

            $token = new Token($user);
            $manager->persist($user);
            
            $manager->flush();

            $tokenSendler->sendToken($user, $token);

            $this->addFlash(
                'notice',
                "Un email de confirmation vous a été envoyé, veuillez cliquer sur le lien présent dans l'email"
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("confirmation/{value}", name="token_validation")
     */
    public function valdateToken(Token $token, TokenRepository $tokenRepository,  EntityManagerInterface $manager, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, Request $request)
    {
        // si on met token au param de la route 
            // $token = $tokenRepository->findOneBy(['value' => $token]);

            // if(null === $token){
            //     throw new NotFoundHttpException();
            // }

            $user = $token->getUser();

            if($user->getEnable()){
                $this->addFlash(
                    'notice',
                    "Le token est deja validé"
                );

            }

            if($token->isValid()){

            $user->setEnable(true);
            $manager->flush($user);

            //je midentifie
            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $loginFormAuthenticator,
                    'main'
            );
        }
        $manager->remove($user);
        $manager->remove($token);

        $this->addFlash(
            'notice',
            "Le token est expiré, inscrivez vous a nouveau"
        );

        return $this->redirectToRoute('registration');
    }
}
