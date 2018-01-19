<?php


namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

class AuthenticationEventsSubscriber implements EventSubscriberInterface
{

    private $session;
    private $maxAuthenticationFailure;

    public function __construct(SessionInterface $session, int $maxAuthenticationFailure)
    {
        $this->session = $session;
        $this->maxAuthenticationFailure = $maxAuthenticationFailure;
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'authenticationFailure'
        ];
    }

    public function authenticationFailure(AuthenticationFailureEvent $event)
    {
        /*
         * session: ne pas utiliser $_SESSION
         *      service : SessionInterface
         *      méthodes:
         *          set(key, valeur) : créer ou mettre � jour une entrée
         *          get(key) : récupérer la valeur d'une entrée
         *          has(key) : tester l'existence d'une entrée
         *          remove(key) : supprimer une entrée
         *          clear(): vider la session
         *          invalidate() : stopper la session
         */
        // tester si la key existe
        if($this->session->has('authentication_failure')){
            // récupération de la valeur
            $value = $this->session->get('authentication_failure');

            // si les 3 échecs ne sont pas atteints
            if($value < $this->maxAuthenticationFailure)
            {
                $value += 1;
                $this->session->set('authentication_failure', $value);
            }

        }
        else
            {
                 $this->session->set('authentication_failure', 1);
            }
           // dump($this->session->get('authentication_failure'));
    }

}







