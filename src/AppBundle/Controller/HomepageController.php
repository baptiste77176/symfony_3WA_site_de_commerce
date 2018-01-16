<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, ManagerRegistry $doctrine):Response
    {
        // récupération de la langues
        $locale = $request->getLocale();

        //récupération des catégories
        $categories = $doctrine->getRepository(Category::class)->getCategoriesByLocaleWithProductsCount($locale);
        $products = $doctrine->getRepository(Product::class)->getProductsByLocale($locale);
       // dump($categories);exit;
        return $this->render('homepage/index.html.twig', [

        'categories' => $categories,
            'products'=>$products

        ]);
    }
}
