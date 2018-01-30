<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search.index")
     */
    public function indexAction(Request $request, ManagerRegistry $doctrine):Response
    {
        //récuperation de la saisie
        //$request ->request équivalent $_REQUEST
    $search = $request->request->get('search');


        // récupération de la locale
        $locale = $request->getLocale();
        // récupération des catégories
        $categories = $doctrine->getRepository(Category::class)->findAll();
        //récupération de sproduit
        //si pas de recherche
        if (!$search)
        {
            $products = $doctrine->getRepository(Product::class)->findAll();
        }
        else
        {
            $products = $doctrine->getRepository(Product::class)->getSearchResults($search, $locale);
        }
        return $this->render('search/index.html.twig', [
        'categories' => $categories,
            'products'=> $products
        ]);
    }
}
