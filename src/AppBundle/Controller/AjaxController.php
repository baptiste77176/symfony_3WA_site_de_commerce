<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 25/01/18
 * Time: 16:20
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\ExchangeRate;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/ajax")
 */
class AjaxController extends Controller
{


    /**
     * @Route("/convert", name="ajax.convert")
     */
    public function convertAction(Request $request, ManagerRegistry $doctrine, SessionInterface $session )
    {
        $selectValue = $request->get('selectValue');
        $query = $doctrine->getRepository(ExchangeRate::class)->findOneBy(array('code'=> $selectValue));
        //dump($query);
        //avec query je peut recuperer les info de l'entité via ->get
        if(!$session->has('queryConvert')){
            $session->set('queryConvert', [
                'code'=> $query->getCode(),
                'taux'=>  $query->getTaux()
            ]);
        }

        $response = new JsonResponse([
            'success' => 'OK'
        ]);
        return $response;
    }




    /**
     * @Route("/search", name="ajax.search")
     */
    public function searchAction(Request $request, ManagerRegistry $doctrine):Response
    {
        // r�cup�ration de la variable POST envoy�e par le JS
        $selectValue = $request->get('selectValue');

        //produits de la categorie
        $category = $doctrine->getRepository(Category::class)->find($selectValue);
        // supprimer les référence circulaire avec les propirété bidirectionnelles
        $objectNormalizer = new ObjectNormalizer();
        $objectNormalizer->setCircularReferenceHandler(function($obj){
            return $obj;
        });
        /*
         * normalizer : format d'entrée des données
         * encoder  :format de sortie des données
         * */
        $normalizers = [$objectNormalizer];
        $encoders = [ new JsonEncoder(), new XmlEncoder()];

        //serializer

        $serializer = new Serializer($normalizers, $encoders);
         // serialisation

        $results = $serializer->serialize($category, 'json');

        // réponse
        $response = new Response($results);
    return $response;
    }








    /**
     * @Route("/cookies-disclaimer", name="ajax.cookies.disclaimer")
     *
     */
    public function cookiesDisclaimerAction(Request $request, SessionInterface $session):JsonResponse
    {
        //récupération de la variable POST envoyé par le JS via ajax data
        $disclaimerValue = $request->get('disclaimerValue');
        //dump($disclaimerValue);
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