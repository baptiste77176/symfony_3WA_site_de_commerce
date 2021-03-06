<?php

namespace AppBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{

    public function getNameBySearchResults(string $locale,string $searchValue):array
    {
        $results = $this->createQueryBuilder('product')
            ->select('translations.name')
            ->join('product.translations', 'translations')
            ->where('translations.locale = :locale')
            ->andWhere('translations.name LIKE :search OR translations.description LIKE :search')
            ->setParameters([
                'locale' => $locale,
                'search' => '%'.$searchValue.'%'
            ])
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()

        ;
        return $results;
    }

    public function getProductsByLocale($locale)
    {
        $result = $this->createQueryBuilder('products')
            ->select('translations.name, products.price, products.image')
            ->join('products.translations','translations')
            ->where('translations.locale = :paramTrans')
            ->setParameters([
                'paramTrans' => $locale
            ])
            ->orderBy('RAND()')
            ->setMaxResults(3)
            ->getQuery()
            ->getArrayResult();
        return $result;
    }
    public function getProduct($categoryslug, $locale)
    {
        $result = $this->createQueryBuilder('product')
            ->select(' product.price')
            ->join('product.translations','translations')
            ->where('translations.slug = :paramSlug')
            ->setParameters([
                'paramSlug'=> $categoryslug
            ])

            ->getQuery()
            ->getArrayResult();
        return $result;
    }

    public function getSearchResults(string $search,string $locale):array
    {
        $results = $this->createQueryBuilder('product')
        ->join('product.translations', 'translations')
        ->where('translations.locale = :locale')
        ->andWhere('translations.name LIKE :search OR translations.description LIKE :search')
        ->setParameters([
            'locale' => $locale,
            'search' => '%'.$search.'%'
        ])
        ->getQuery()
        ->getResult()

        ;
        return $results;

    }
}
