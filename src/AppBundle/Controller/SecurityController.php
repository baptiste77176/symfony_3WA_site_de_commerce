<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 16/01/18
 * Time: 14:26
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security.login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils, SessionInterface $session)
    {
        //récupération du nombre d'echec de connexion : AuthenticationEventsSubscriber
        if ($session->has('authentication_failure') && $session->get('authentication_failure')===3)
        {
            // reinitialisation
            $session->remove('authentication_failure');

            //message flash
            $this->addFlash('notice', ' Vous semblez avoir oublié vos id');

            //redirection
            return $this->redirectToRoute('account.password.forgot');
        }



        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }
    /**
     * @Route("/logout", name="security.logout")
     */
    public function logout()
    {
        //méthode non appelée par symfony
    }
    /**
     * @Route("/redirect-by-role", name="security.redirect.by.role")
     */
    public function redirectByRole()
    {//recuperation de l'utilisisateur
        $user = $this->getUser();

        // récupération du role
        $roles = $user->getRoles();
        //dump($roles);exit;

        //test sur le role
        if (in_array('ROLE_ADMIN',$roles))
        {
            return $this->redirectToRoute('admin.homepage.index');
        }
        else{
            return $this->redirectToRoute('profile.homepage.index');
        }
    }
}
