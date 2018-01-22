<?php

namespace AppBundle\Controller\Profile;

use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 * @Route("/profile")
 *
 */
class ManageController extends Controller
{
    /**
     * @Route("/manage", name="profile.manage.index")
     */
    public function indexAction(Request $request):Response
    {
        // récupération de l'utilisateur
        $user = $this->getUser();
        dump($user);
        //formulaire
        $type = UserType::class;
        $form = $this->createForm($type,$user);
        $form->handleRequest($request);
        //formulaire valide
        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            dump($data);
        }

        return $this->render('profile/manage/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
