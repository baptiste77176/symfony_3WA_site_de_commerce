<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{

    private $locales;


    public function __construct(array $locales)
    {
        $this->locales = $locales;

    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        // créer plusieurs champs selon les langues
        foreach ($this->locales as $key => $value) {

            /*
             * mapped : permet de définir si un champ est relié a une propriété de l'entité; par defaut true
             * data : permet de definir une valeur au champ
             * */
            //champ name
            //double " pour concaterner comme ca
            $builder
                ->add("name_$value", TextType::class, [
                    'mapped' => false,
                    'data' => $entity->translate($value)->getName()
                ])
                //champs description
                ->add("description_$value", TextareaType::class, [
                    'mapped' => false,
                    'data' => $entity->translate($value)->getDescription()
                ]);
            $builder
                ->add('price')
                ->add('image', FileType::class, [
                    'data_class' => null
                ])
                ->add('stock')
                ->add('category', EntityType::class, [
                    'class' => Category::class,
                    'choice_label' => 'id',
                ]);
            //formevents famille d'événement a ecouter : pre_submit
            //form event =  formulaire en cours
            //écouteur : récupere les saisies et de fusionner les traductions
            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();

                // données du formulaire
                $entity = $event->getForm()->getData();
                // création des traduction
                foreach ($this->locales as $key => $value) {
                    $entity->translate($value)->setName($data["name_$value"]);
                    $entity->translate($value)->setDescription($data["description_$value"]);
                    // méthode mergeNewTranslation est fournie par doctrine behaviors

                }
                //dump($data, $entity);
                $entity->mergeNewTranslations();
            });
        }
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
