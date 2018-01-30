<?php

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @Route("/admin")
 *
 */
class CommandController extends Controller
{

    /**
     * @Route("/mysql-dump", name="admin.command.mysql.dump")
     */
    public function mysqlDumpAction(KernelInterface $kernel, ManagerRegistry $doctrine):Response
    {

        //application
        $application = new Application($kernel);
        $application->setAutoExit(false);

        //équivalent à configure()
        $input = new ArrayInput(array(
            'command' => 'app:mysql:dump',
        ));

        //sortie de la console
        $output = new BufferedOutput();
        $application->run($input, $output);

       return $this->render('admin/command/mysqldump.html.twig',[
           'result' => $output->fetch()
       ]);
    }

}
/*
 *
 *
 * note hébergeur
 *
 *
 *
 * - se renseigner sur la version du php
 * - nom de domaine doit pointer vers le dossier web
 * - vider dans le dossier var cache var log var session
 * - ne pas mettre en ligne le dossier vendor car trop gros mais plutot envoye rle composer json et faire en ligne de command eun composer install cra beaucoup plus rapide
 * - na pas mettre en ligne le dossier test car inutile
 * -parameter.yml pour la prod et config_dev
 * -import de la database
 * -les identifiant du ftp sont fournie par l'hébergeursi acces a la ligne de commande git clone est mieu pour dupliquer le repository
 * -donner les droit dacces au dossier avec chmod
 *
 *
 * */