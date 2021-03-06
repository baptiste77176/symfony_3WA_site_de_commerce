<?php

namespace AppBundle\Controller\Profile;

use AppBundle\Form\UserType;
use Doctrine\Common\Persistence\ManagerRegistry;
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
    public function indexAction(Request $request, ManagerRegistry $doctrine):Response
    {
        // récupération de l'utilisateur
        $user = $this->getUser();
        dump($user);
        //formulaire
        $type = UserType::class;
        $form = $this->createForm($type,$user);
        $form->handleRequest($request);
        //formulaire valide
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $doctrine->getManager()->persist($data);
            $doctrine->getManager()->flush();
            $this->addFlash('notice', 'Votre profil a �t� compl�t�');
            return $this->redirectToRoute('profile.homepage.index');
        }

        return $this->render('profile/manage/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
