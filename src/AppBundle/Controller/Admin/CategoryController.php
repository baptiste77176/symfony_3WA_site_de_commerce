<?php

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 *
 */
class CategoryController extends Controller
{








    /**
     * @Route("/category/form", name="admin.category.form", defaults={"id"= null})
     * @Route("/category/update/{id}", name="admin.category.update")
     */
    public function formAction(ManagerRegistry $doctrine, Request $request, $id):Response
    {


        //doctrine
        $em = $doctrine->getManager();
        $rc =  $doctrine->getRepository(Category::class);


        $entity = $id ? $rc->find($id) : new Category();
        $type = CategoryType::class;
        $form  = $this->createForm($type, $entity);
        $form->handleRequest($request);
        // formulaire valide



        if ($form->isSubmitted() && $form->isValid()) {
            //récupération de la saisie
            $data = $form->getData();
            dump($data);

            //insertion en base
            $em = $doctrine->getManager();
            $em->persist($data);
            $em->flush();
            $this->addFlash('notice', $id ? 'la categorie a ete modifier' : 'la catégorie a ete créer');
            return $this->redirectToRoute('admin.category.index');

        }
        return $this->render('admin/category/form.html.twig', [
            'form'=>$form->createView()
        ]);
    }



    /**
     * @Route("/category", name="admin.category.index")
     */
    public function indexAction(ManagerRegistry $doctrine):Response
    {
        $rc = $doctrine->getRepository(Category::class);
        $results = $rc->findAll();
        return $this->render('admin/category/index.html.twig', [
            'results' => $results
        ]);
    }
    /**
     * @Route("/category/delete/{id}", name="admin.category.delete")
     */
    public function deleteAction(ManagerRegistry $doctrine,int $id):Response
    {
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Category::class);
        $entity = $rc->find($id);
        $em->remove($entity);
        $em->flush();
        $this->addFlash('notice', 'la catgeorie a été suprimer');
        return $this->redirectToRoute('admin.category.index');
    }

}
