<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 29/01/18
 * Time: 12:50
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class MysqlDumpCommand extends Command
{

    // envoie un mail avec un zip de la sauvegarde de la bdd

    private $mailer;

    public function __construct(\Swift_Mailer $mailer, $name = null)
    {
        $this->mailer =  $mailer;
        parent::__construct($name);
    }

    protected function configure()
    {
       $this
           ->setName('app:mysql:dump')
           ->setDescription('Export Database ans send it by mail')

       ;
    }


        protected function execute(InputInterface $input, OutputInterface $output)
    {
        // date du jour
        $date = new \DateTime();
        $dateFormat = $date->format('Y-m-d');

        // export de la base
        $command = "mysqldump -u root -ptroiswa commerce > $dateFormat-commerce.sql";

        //zip
        $command .= " && zip $dateFormat-commerce.zip $dateFormat-commerce.sql";


        //suppression de l'export de sql
        $command .= "&& rm $dateFormat-commerce.sql";

        // process: acc�s au terminal de l'OS
        $process = new Process($command);

        // ex�cution du process
        $process->run();

        //message de l'email
        //attach est la piece jointe
        $message = (new \Swift_Message("$dateFormat - dump mysql"))
            ->setFrom('contact@contact.com')
            ->setTo('admin@admin.com')
            ->setBody("$dateFormat - dump mysql")
            ->attach(\Swift_Attachment::fromPath("$dateFormat-commerce.zip"))
            ;

        //envoie  de l'email
        $this->mailer->send($message);
        // r�cup�ration de la sortie du terminal
        $outputProcess = $process->getOutput();

        // sortie
        $output->writeln($outputProcess);
        $output->writeln("Mysql dump sended by mail");
    }


}