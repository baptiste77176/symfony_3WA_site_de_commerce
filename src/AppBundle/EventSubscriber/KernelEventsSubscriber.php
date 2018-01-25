<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 25/01/18
 * Time: 09:39
 */

namespace AppBundle\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelEventsSubscriber implements EventSubscriberInterface
{/*
on dit au kernel que lon se sert de l'event kernelEvents on rajoute des action au request
*/

    private $twig;
    private $maintenanceEnable;
    private $session;

    public function __construct(\Twig_Environment $twig, bool $maintenanceEnable, SessionInterface $session)
    {
        $this->twig = $twig;
        $this->maintenanceEnable = $maintenanceEnable;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        /*
         * définir plusieurs méthodes a un événement
         *      créer un array de array : parametres
         *              - nom de la méthode
     *                  - priorité de l'événement ( par defaul 0 )
         *
         * quand plusieyur methode relier au meme evenement il faut faire un tableau de tableau
         * */

        return [
            KernelEvents::REQUEST => 'maintenanceMode',
            KernelEvents::RESPONSE => [
                ['redirect404', 1],
                ['addSecurityHeaders'],
                ['cookiesDisclaimer']
            ]

        ];
    }

    public function cookiesDisclaimer(FilterResponseEvent $event)
    {
        //session
        if(!$this->session->has('cookiesDisclaimer'))
        {
            //key : cookiesDisclaimer / value : true
            $this->session->set('cookiesDisclaimer', true);
        }

        $sessionValue = $this->session->get('cookiesDisclaimer');



        //récupération du contenu
        $content = $event->getResponse()->getContent();
        //dump($content);exit;

        //remplacement de contenu si la key en session est true
        if ($sessionValue)
        {
            $content = str_replace('<body>','<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Ce site utilise des cookies </strong>
  <button type="button" class="close close-cookies-disclaimer" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>',$content);
        }

        //remplacement du contenu

        //retourner le resultat
        $response = new Response($content);
        $event->setResponse($response);
    }

    public function addSecurityHeaders(FilterResponseEvent $event)
    {
        $response = new Response(
            $event->getResponse()->getContent(),
            $event->getResponse()->getStatusCode(),
            ['Content-Security-Policy'=>"default-src 'self'",
                'X-Content-Type-Options'=>' nosniff',
                'X-Frame-Options'=>' DENY',
                'X-XSS-Protection'=>' 1; mode=block'
                ]
        );
        $event->setResponse($response);


    }

    public function redirect404(FilterResponseEvent $event)
    {
        // code HTTP
        $code = $event->getResponse()->getStatusCode();
       // dump($code);
        //404 page non trouver
        if($code === 404)
        {
            $response = new RedirectResponse('/fr/');

            //retourner une réponse
            $event->setResponse($response);
        }

    }


    public function maintenanceMode(GetResponseEvent $event)

    {
        //si la maintenance dans maintenance.yml
        if ($this->maintenanceEnable)
        {
            $content = $this->twig->render('index.html.twig');
            $reponse = new Response($content, 503);




            //retourner la reponse
            $event->setResponse($reponse);
        }


        //dump($event);

        /*
         * modifier / remplacer la réponse
         *      $event->setResponse :  envoie d'une nouvelle réponse  (Response / RedirectResponse / JsonResponse)
         * tout ce qui est relier a un http doit utiliser un use avec httpfoun dation
         *
         *
         * */
        //contenu de la réponse






    }

}