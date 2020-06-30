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
     * @Route("/profil/{id}/{csrf}", name="modifier_profil", requirements={"id": "\d+"})
     */
    public function modifierProfil($id, $csrf,UserRepository $ur, EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder){
        if (!$this->isCsrfTokenValid('modifier_profil_' . $id, $csrf)) {
            throw $this->createAccessDeniedException('Désolé, votre session a expiré !');
        } else {
            $user = $ur->find($id);
            $userForm = $this->createForm(UserType::class, $user);

            $userForm->handleRequest($request);
            if ($userForm->isSubmitted() && $userForm->isValid()) {
                //Hasher le mot de passe
                $hashed = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hashed);

                $em->flush();
                $this->addFlash('success', "Profil modifié");
                return $this->redirectToRoute('modifier_profil', ["id" => $id]);
            }
        }
        return $this->render('user/profil.html.twig', [
            "userForm" => $userForm->createView(),
            "user" => $user
        ]);
    }
}
