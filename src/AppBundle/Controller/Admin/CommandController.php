<?php

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Application;
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
