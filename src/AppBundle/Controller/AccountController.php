<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 16/01/18
 * Time: 16:25
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /**
     * @Route("/register", name="account.register")
     */
    public function registerAction(ManagerRegistry $doctrine, Request $request): Response
    {

        //création d'un formulaire
        $entity = new User();
        $type =UserType::class;
        $form  = $this->createForm($type, $entity);
        $form->handleRequest($request);
        // formulaire valide
        if ($form->isSubmitted() && $form->isValid())
        {
            //récupération de la saisie
            $data = $form->getData();
            dump($data);exit;
        }
        return $this->render('account/register.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}