<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 12/01/18
 * Time: 12:29
 */

namespace AppBundle\DataFixtures;


use AppBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
                       //cléz    valeur
    private $locales = ['en' => 'EN_US', 'fr' => 'fr_FR'];

    public function load(ObjectManager $manager)
    {


        for($i = 0 ; $i < 4 ; $i++)// si 4 category == 8 traduction
        {
            // cibler les propriétés non traduites
            $entity = new Category();//création de 4 category
            foreach ($this->locales as $key => $value)
            {
                // faker en fonction de la valeur du parametre il genere une phrase en fr ou ganglais via realtext
                $faker = \Faker\Factory::create($value);

                // créer des valeurs traduites pour les propriétés
                $name = ($key === 'fr') ? 'catégorie' : 'category';
                //$description = ($value === 'fr') ? 'description' : 'description';
                $description = $faker->realText();
                // methode translate fournie par octrine behaviors
                $entity->translate($key)->setName($name.$i);
                $entity->translate($key)->setDescription($description);

                //la methode mergeNewTranslations est fournie par doctrine behaviors

            }
            $entity->mergeNewTranslations();

            // stocke les catégories en mémoire
            //création de 4 reference en memoire, 4 categorie / clé valeur
            $this->addReference('category'.$i, $entity);

            $manager->persist($entity);
        }
        $manager->flush();
    }

}