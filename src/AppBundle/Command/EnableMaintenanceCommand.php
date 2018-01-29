<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 29/01/18
 * Time: 09:39
 */

namespace AppBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnableMaintenanceCommand extends Command
{
    /*
     * le but est de mettre en ligne une commande php bin/console doctrine:mainetance:enable avec true ou false pour mettre le site en maintenance ou pas 
     * configuration de la commande
     *      setname: nom de la commande ; obligatoire
     *      setDescription: description
     *      setHelp : aide // accessible via option -h
     *      addArgument : ajouter un arguments par default optionnel
     *      addOption : ajouter une option
     *
     * */
    protected function configure()
    {
        $this->setName('app:maintenance:enable');
        $this->setDescription('enable or disable maintenance mode');
        $this->setHelp('you must use true or false as value');
        $this->addArgument('value', InputArgument::REQUIRED, 'use true or false');
    }


    /*
     * execution de la commande
     *      $input : permet de recupérér les argument et les options definis dans configure option
     *      $output: afiche de la sortie de la console
 *          style: <input> / <error> / <question> / <comment>
     * */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
       // récupération de l'argument value
        $value = $input->getArgument('value');

        //tester la valeur saisie
        if ($value !== "true" && $value !== "false")
        {
            throw new InvalidArgumentException('You must use true or false as value');
            //sortie

        }
        //import du fichier
        $file = file_get_contents('app/config/maintenance.yml');
        $content = preg_replace('/maintenance_enable: (true|false)/',"maintenance_enable: $value", $file);
        // modification du contenu
        file_put_contents('app/config/maintenance.yml', $content);
        $message = ($value == "true") ? 'Maintenance<comment>enable</comment>' : 'Maintenance<comment>disable</comment>';
        dump($content);

        $output->writeln($message);
       // $output->writeln("<error>yolo life</error>");
        //$output->writeln("<question>yolo life</question>");
       // $output->writeln("<comment>yolo life</comment>");
    }

}