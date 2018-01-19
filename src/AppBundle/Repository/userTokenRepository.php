<?php

namespace AppBundle\Repository;

/**
 * userTokenRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class userTokenRepository extends \Doctrine\ORM\EntityRepository
{

    public function getDateMax($date, $mail)
    {
        $result = $this->createQueryBuilder('userToken')
            ->select('userToken.expirationDate')
            ->where('CURRENT_DATE() < :paramDate')
            ->andWhere('userToken.userEmail = :paramMail')

            ->setParameters([
                'paramDate' => $date,
                'paramMail' => $mail
            ])
            ->getQuery()
            ->getArrayResult();
        return $result;
    }
}
