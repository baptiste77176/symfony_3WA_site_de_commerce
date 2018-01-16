<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @Route("/c/{slug}", name="category.index")
     */
    public function indexAction(Request $request, ManagerRegistry $doctrine, $slug):Response
    {
        $locale = $request->getLocale();
        $category = $doctrine->getRepository(Category::class)->getCategoryBySlugAndLocale($slug, $locale);
       // dump($category);//exit;
        return $this->render('category/index.html.twig', [

        'category'=>$category
        ]);
    }
}
