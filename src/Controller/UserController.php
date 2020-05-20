<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * fonction permettant de se connecter
     */
    public function login(AuthenticationUtils $au)
    {
        $error = $au->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $au->getLastUsername();

        return $this->render('user/login.html.twig', [
            "error" => $error,
            "lastusername" => $lastUsername,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * Fonction permettant la deconnexion
     */
    public function logout()
    {
    }

    /**
     * @Route("/addUser", name="user_add")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, UserRepository $ur){
        $user = new User();
        $registerForm = $this->createForm(UserType::class, $user);
        $user->setRoles(['ROLE_ADMIN']);

        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid() ){
            //Hasher le password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('main');
        }
        return $this->render('user/register.html.twig', [
            "registerForm" => $registerForm->createView(),
        ]);
    }
}
