<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 22/01/18
 * Time: 16:19
 */

namespace AppBundle\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;

class UserTypeSubscriber implements EventSubscriberInterface
{

    private $request;

    public function __construct(RequestStack $request)
    {

        //masterRequest : cibler la requete principale
        $this->request = $request->getMasterRequest();
    }
    public static function getSubscribedEvents()
    {
        return [
          FormEvents::PRE_SET_DATA => 'preSetData'
        ];
    }

    public function preSetData(FormEvent $event)
    {
        // le but est de recuperer la route
        $route = $this->request->get('_route');


        // r�cup�ration de la saisie
        $data = $event->getData();

        // formulaire
        $form = $event->getForm();
        dump($data, $form);

        if ($route === 'profile.manage.index')
        {
            //suppression des champs qui ne nous interrese pas
            $form->remove('username');
            $form->remove('password');
            $form->remove('email');
        }
    }

}