<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 29/01/18
 * Time: 12:50
 */

namespace AppBundle\Command;


use AppBundle\Entity\userToken;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteExpiredUserTokensCommand extends Command
{

    // supprime les tokens qui on expiré

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, $name = null)
    {
        $this->doctrine = $doctrine;
        parent::__construct($name);
    }

    protected function configure()
    {
       $this
           ->setName('app:delete:expired:tokens')
           ->setDescription('delete expired reset password tokens in database')

       ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //suprpession des tokens
        $delete = $this->doctrine->getRepository(userToken::class)->deletedExpiratedTokens();

        //sortie

        $output->writeln('Expired toekns <comment>deleted</comment>');
    }

}