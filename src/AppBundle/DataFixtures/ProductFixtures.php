<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 12/01/18
 * Time: 12:29
 */

namespace AppBundle\DataFixtures;


use AppBundle\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
                       //cléz    valeur
    private $locales = ['en' => 'EN_US', 'fr' => 'fr_FR'];

    public function load(ObjectManager $manager)
    {


        for($i = 0 ; $i < 50 ; $i++)// si 4 category == 8 traduction
        {
            // faker
            $faker = \Faker\Factory::create();

            // cibler les propriétés non traduites
            $entity = new Product();//création de 4 category
            $entity->setPrice($faker->randomFloat(2,1,999.99));
            $entity->setStock($faker->numberBetween(0,100));

            /*image : cible la racine du projet
                    le dossier ciblé doit exister
            */
            $entity->setImage(
                $faker->image('web/img/product', 400, 400, 'cats', false)
            );
            $randomCategory = $faker->numberBetween(0,3);
            //associer le produit a une categorie
            $entity->setCategory(
                $this->getReference('category'.$randomCategory)
            );
            foreach ($this->locales as $key => $value)
            {
                // faker en fonction de la valeur du parametre il genere une phrase en fr ou anglais via realtext
                $faker = \Faker\Factory::create($value);

                // créer des valeurs traduites pour les propriétés
                $name = ($key === 'fr') ? 'produit' : 'product';

                //$description = ($value === 'fr') ? 'description' : 'description';

                $description = $faker->realText();
                // methode translate fournie par octrine behaviors

                $entity->translate($key)->setName($name.$i);
                $entity->translate($key)->setDescription($description);

                //la methode mergeNewTranslations est fournie par doctrine behaviors

            }

            $entity->mergeNewTranslations();



            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }

}