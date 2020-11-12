<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/user/logout")
     * @IsGranted("ROLE_USER")
     */
    public function logout()
    {
        throw new \Exception('Will be intercepted before getting here');
    }

    /**
     * @Route("/register")
     */
    public function register(){

        return $this->render('security/register.html.twig');
    }

    /**
     * @Route("/register/user", name="app_security_registeruser")
     */
    public function registerUser(Request $request, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils, LoginFormAuthenticator $login, GuardAuthenticatorHandler $guard){

        $user = new User();

        #Llamando datos para construir el objeto
        $email = $request->get('email');
        $fullName = $request->get('fullname');
        $password = $request->get('password');

        #Asignando un slug único con uniqid para la url del usuario
        $fullNameParsed = ucwords(str_replace(' ','-',$fullName));
        $slug = $fullNameParsed.'-'.uniqid();

        #codificando la contraseña
        $encoded = $this->passwordEncoder->encodePassword($user, $password);

        #Creando el objeto
        $user->setEmail($email);
        $user->setFullname($fullName);
        $user->setPassword($encoded);
        $user->setSlug($slug);
        $user->setRoles([]);

        #Guardando el usuario en la BD
        $entityManager->persist($user);
        $entityManager->flush();

        // Logeando luego de registrarse
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username ingresado por el usuario
        $lastUsername = $authenticationUtils->getLastUsername();

        return $guard->authenticateUserAndHandleSuccess($user,$request,$login,'main');
    }
}
