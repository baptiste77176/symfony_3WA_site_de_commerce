<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 25/01/18
 * Time: 16:20
 */

namespace AppBundle\Controller;


use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/ajax")
 */
class AjaxController extends Controller
{
    /**
     * @Route("/cookies-disclaimer", name="ajax.cookies.disclaimer")
     *
     */
    public function cookiesDisclaimerAction(Request $request, SessionInterface $session):JsonResponse
    {
        //récupération de la variable POST envoyé par le JS via ajax data
        $disclaimerValue = $request->get('disclaimerValue');
        dump($disclaimerValue);
        /*
         * route appelée en ajax avc symfony
         *      - pas de vues
         *      - la route retourne du json avec jsonresponse
         *
         * */

        //modification de la valeur en session
        $session->set('cookiesDisclaimer',$disclaimerValue);


        $response = new JsonResponse([
            'success' => 'OK'
        ]);
        return $response;

    }
}