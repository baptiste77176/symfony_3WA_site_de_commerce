<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 16/01/18
 * Time: 16:25
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Events\AccountCreateEvent;
use AppBundle\Events\AccountEvents;
use AppBundle\Form\UserType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class AccountController extends Controller
{
    /**
     * @Route("/register", name="account.register")
     */
    public function registerAction(ManagerRegistry $doctrine, Request $request, TranslatorInterface $translator, EventDispatcherInterface $dispatcher): Response
    {

        //création d'un formulaire
        $entity = new User();
        $type = UserType::class;
        $form  = $this->createForm($type, $entity);
        $form->handleRequest($request);
        // formulaire valide



        if ($form->isSubmitted() && $form->isValid())
        {
            //récupération de la saisie
            $data = $form->getData();


            //insertion en base
            $em = $doctrine->getManager();
            $em->persist($data);
            $em->flush();

            //dump($data);exit;
            //message flash
            $this->addFlash('notice', $translator->trans('flash_message.new_user'));

            //déclencher l'événement AccountEvents::CREATE
            //element declencheur = $dispatcher
            //evenement
            $event = new AccountCreateEvent();

            $dispatcher->dispatch(AccountEvents::CREATE, $event);
            exit;
            // redirection
            return $this->redirectToRoute('security.login');

        }
        return $this->render('account/register.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}