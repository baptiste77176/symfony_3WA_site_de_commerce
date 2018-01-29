<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 29/01/18
 * Time: 10:54
 */

namespace AppBundle\Command;




use AppBundle\Entity\ExchangeRate;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRateCommand extends Command
{
    //convertir le taux de change en ligne de commande depuis l'api
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine ,$name = null)
    {
        $this->doctrine = $doctrine;
        parent::__construct($name); // on laisse ce parametres
    }


    protected function configure()
    {
        $this
            ->setName('app:exchange:rate:update')
            ->setDescription('Update exchange rate in database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //récupération des taux de change
        $result  = json_decode(file_get_contents('https://api.fixer.io/latest?symbols=USD,GBP'));
        dump($result->rates);

        // mise a jour dans la table
        $update = $this->doctrine->getRepository(ExchangeRate::class)->updateExchangeRate($result->rates);
       $output->writeln('exchange rates <question>updated</question>');
       foreach ($result->rates as $key =>$value){
           $output->writeln("$key<question>$value</question>");
       }
    }

}