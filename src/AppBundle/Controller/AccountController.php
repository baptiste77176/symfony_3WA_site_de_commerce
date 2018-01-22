<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 16/01/18
 * Time: 16:25
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Entity\userToken;
use AppBundle\Events\AccountCreateEvent;
use AppBundle\Events\AccountEvents;
use AppBundle\Form\RecoveryType;
use AppBundle\Form\UserTokenType;
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
            $event->setUser($data);

            $dispatcher->dispatch(AccountEvents::CREATE, $event);

            // redirection
            return $this->redirectToRoute('security.login');

        }
        return $this->render('account/register.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/password-forgot", name="account.password.forgot")
     *
     */
    public function passwordForgotAction(ManagerRegistry $doctrine,EventDispatcherInterface $dispatcher,Request $request,\Swift_Mailer $mailer):Response
    {
        $entity = new userToken();
        $type = UserTokenType::class;
        $form  = $this->createForm($type, $entity);
        $form->handleRequest($request);
       // dump($entity);

        if ($form->isSubmitted() && $form->isValid()) {
            //récupération de la saisie
            $data = $form->getData();


            // utilisateur existant
            $emailExist = $doctrine
                ->getRepository(User::class)
                ->findOneBy([
                    'email' => $data->getUserEmail()
                ])
            ;

            // demande existante
            $dateRequest = $doctrine->getRepository(userToken::class)->getDateMax($data->getExpirationDate(), $data->getUserEmail());
            //$token = $doctrine->getRepository(userToken::class)->getTokenUser($data->getUserEmail());
           // dump($token);exit;

            if($emailExist && !$dateRequest){
                //insertion en base
                $em = $doctrine->getManager();
                $em->persist($data);
                $em->flush();

                // envoi d'un mail
                $message  = (new \Swift_Message('reinitiallisation du mdp '))
                    ->setFrom('contact@website.com')
                    ->setTo($data->getUserEmail())
                     ->setBody('<a href="http://localhost:8000/fr/password-recovery/'. $data->getUserEmail() .'/'. $data->getToken() .'">click here for new password</a>', 'text/html');
                $mailer->send($message);

            }
        }

        return $this->render('account/password.forgot.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/password-recovery/{email}/{token}", name="account.password.recovery")
     */
    public function recoveryPassword($email, $token, ManagerRegistry $doctrine, Request $request ):Response
    {
        $checkin = $doctrine->getRepository(userToken::class)->check($email, $token);
        //dump($checkin);exit;

        if (!$checkin)
        {
            $this->addFlash('notice', ' un email à été envoyé');
            return $this->redirectToRoute('account.password.forgot');

        }else
        {

            $entity = new User();
            $type = RecoveryType::class;//field
            $form  = $this->createForm($type, $entity);

            $form->handleRequest($request);
            // formulaire valide



            if ($form->isSubmitted() && $form->isValid())
            {
                //récupération de la saisie
                $data = $form->getData();

                //dump($data);exit;//faire find one by sur l'email de user et set le password puis addflash
                //insertion en base
                $em = $doctrine->getManager();

                $findGoodUser = $doctrine
                    ->getRepository(User::class)
                    ->findOneBy([
                        'email' => $email
                    ])
                ;
                $findGoodUser->setPassword($data->getPassword());
               // dump($findGoodUser);exit;
                $em->persist($findGoodUser);
                $em->flush();
                // reencoder  danss l'ecouteur event listener avec un preupdate
                return $this->redirectToRoute('security.login');
            }




                return $this->render('account/recovery.html.twig',[
                'form'=>$form->createView()
            ]);
        }
        //exit();

    }




}