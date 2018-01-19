<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 17/01/18
 * Time: 16:13
 */

namespace AppBundle\EventSubscriber;


use AppBundle\Events\AccountCreateEvent;
use AppBundle\Events\AccountEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountEventSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        /*
         * doit retournée un tableau
         *      - clé : l'évenement ecouter
         *      - valuer: nom du gestionnaire d'événement
         * */
        return [
            AccountEvents::CREATE =>'create'
            /*nom de la classe :: constante dans la classe => function*/
        ];
    }

    // un gestionnaire d'événement recoit l'événement en paramètre
    public function create(AccountCreateEvent $event)
    {
        //dump($event);exit;
        /*
          * création du message
          *      service d'emailing : SwiftMailer
          *      message : Swift_Message
          *          setFrom : méthode obligatoire // expéditeur
          *          setTo : destinataire
         *           set body() : corps du message
         *                  - par defaultt :  type plaintext
          *      $mailer->send() : envoie le message
          */
        $message  = (new \Swift_Message('sujet du message '))
        ->setFrom('contact@website.com')
        ->setTo($event->getUser()->getEmail())
       // ->setBody('<h1 style="color: red;">Bienvenue</h1>', 'text/html')
            ->setBody(
                $this->twig->render('emailing/account.create.html.twig',[
                    'data' => $event->getUser()
                ]),'text/html'
       )
        ->addPart(
            $this->twig->render('emailing/account.create.txt.twig',['data'=> $event->getUser()])
        )
            ;
        //envoie du mail
        $this->mailer->send($message);



    }
    /*public function sendMailToForgotAccount()
    {

    }*/

}