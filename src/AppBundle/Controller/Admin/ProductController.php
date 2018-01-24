<?php

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Form\CategoryType;
use AppBundle\Form\ProductType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/admin")
 *
 */
class ProductController extends Controller
{








    /**
     * @Route("/product/form", name="admin.product.form", defaults={"id"= null})
     * @Route("/product/update/{id}", name="admin.product.update")
     */
    public function formAction(ManagerRegistry $doctrine, Request $request, $id, SessionInterface $session):Response
    {


        //doctrine
        $em = $doctrine->getManager();
        $rc =  $doctrine->getRepository(Product::class);


        $entity = $id ? $rc->find($id) : new Product();
        $type = ProductType::class;
        $form  = $this->createForm($type, $entity);
        $form->handleRequest($request);

        if(!$session->has('imageDB')){
            $session->set('imageDB', $entity->getImage());
        }

        $entity->imageDB = $session->get('imageDB');


        //dump($entity);



        // formulaire valide
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $dir = getcwd().'/img/product';
            $file = $data->getImage();



            // si une image a été sélectionnée
            if($file)
            {
                $file->move($dir, $file->getClientOriginalName());

                $entity->setImage($file->getClientOriginalName());
            }
            // si aucune n'a été sélectionnée
            else{
                $data->setImage($data->imageDB);
            }

            //dump($data);exit;

            //$result = $data;
           // dump($data);

            //insertion en base
            $em = $doctrine->getManager();
            $em->persist($data);
            $em->flush();

            $session->remove('imageDB');

        // dump($entity);exit;
            return $this->redirectToRoute('admin.product.index',[
                'result' => $data
            ]);
    }



//dump($form);exit();
        return $this->render('admin/product/form.html.twig', [
            'form'=>$form->createView()
        ]);
    }



    /**
     * @Route("/product", name="admin.product.index")
     */
    public function indexAction(ManagerRegistry $doctrine):Response
    {
        $rc = $doctrine->getRepository(Product::class);
        $results = $rc->findAll();
        return $this->render('admin/product/index.html.twig', [
            'results' => $results
        ]);
    }
    /**
     * @Route("/product/delete/{id}", name="admin.product.delete")
     */
    public function deleteAction(ManagerRegistry $doctrine,int $id):Response
    {
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Product::class);
        $entity = $rc->find($id);
        $em->remove($entity);
        $em->flush();
        $this->addFlash('notice', 'le produit a été suprimer');
        return $this->redirectToRoute('admin.product.index');
    }

}
