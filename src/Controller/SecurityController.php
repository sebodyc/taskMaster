<?php

namespace App\Controller;

use App\Form\RegisterType;
use Symfony\Component\Mime\Address;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig', []);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/register", name="security_register")
     * 
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {

        $form = $this->createForm(RegisterType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $plainPassword = $user->getPassword();
            $hash = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($hash);
            $em->persist($user);
            $em->flush();


            $registerMessage = new MailerController;
            $registerMessage->sendEmail(
                $mailer,
                $user->getEmail(),
                'Merci pour votre inscription',
                'emails/signup.html.twig',
                $user->getfullname()
            );

            $this->addFlash('success', "Vous avez créer un compte un email de confirmation vous a ete envoyé");


            return $this->redirectToRoute("security_login");
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
