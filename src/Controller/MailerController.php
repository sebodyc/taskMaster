<?php

namespace App\Controller;

use App\Entity\User;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;

class MailerController extends AbstractController
{




    /**
     * fonction qui permet l'envoi d'un mail de verification a toute nouvelle personne qui créer un compte 
     * @Route("/email/signup",name ="signup_email")
     * @param MailerInterface $mailer
     * @param string $userMail le mail de l'user qui a creer son compte
     * @param string $message le message de l'objet du mail
     * @param string $template l'adresse de template pour le contenu du mail
     * @param string $userName le nom de l'user qui a creer son compte 
     */
    public function sendEmail(MailerInterface $mailer, string $userMail, string $message, String $template, string $userName)
    {



        $email = (new TemplatedEmail())
            ->from('taskmaaster@gmail.com')
            ->to($userMail)
            ->subject($message)

            // path of the Twig template to render
            ->htmlTemplate($template)

            // pass variables (name => value) to the template
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => $userName,
            ]);

        $mailer->send($email);

        // // ...


    }

    /**
     * fonction qui permet l'envoi d'email aux colaborateurs a chaque nouvelle tache ajoutée au projet
     *@Route("/email/newTask",name ="newTask_email")
     *@param MailerInterface $mailer
     * @param string $userMail le mail des colaborateur du projet
     * @param string $message le message a envoyer dans l'objet du mail
     * @param string $template l'adresse de template pour le contenu du mail
     * @param string $userName le nom du colaborateur
     * @param string $project le nom du projet
     */
    public function sendEmailNewTask(MailerInterface $mailer, string $UserMail, string $message, string $template, string $userName, string $project)
    {



        $email = (new TemplatedEmail())
            ->from('taskmaaster@gmail.com')
            ->to($UserMail)
            ->subject($message)

            // path of the Twig template to render
            ->htmlTemplate($template)

            // pass variables (name => value) to the template
            ->context([
                'projects' => $project,
                'username' => $userName,
            ]);

        $mailer->send($email);


    }
}
