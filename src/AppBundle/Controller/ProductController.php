<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/p/{categoryslug}/{productslug}", name="product.index")
     */
    public function indexAction(Request $request, ManagerRegistry $doctrine, $categoryslug):Response
    {
        $locale = $request->getLocale();
        $product = $doctrine->getRepository(Product::class)->getProduct($categoryslug, $locale);
       // dump($product);//exit;
        return $this->render('product/index.html.twig', [

            'product'=>$product
        ]);

    }
}
