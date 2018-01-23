<?php

namespace AppBundle\Form;

use AppBundle\EventSubscriber\UserTypeSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{

    //injecter la pile de requete
    private  $requestStack; // pour instancier le new
    private $request;//pour le get _route

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        //quand on va chercher la branche master cela nous renvoie la meme requet que dans les controleurs
        $this->request = $requestStack->getMasterRequest();

    }




    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                new NotBlank([
                    'message' => 'username'
                ])
    ]
                ])
            ->add('password', PasswordType::class,[
                'constraints'=> [
                    new NotBlank([
                        'message' => 'password'
                    ])
                ]
            ])
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'email.incorrect'
                    ])
                ]
            ])
            ->add('address',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'adresse vide'
                    ])
                ]
            ])
            ->add('zipcode',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'zipcode incorrect'
                    ])
                ]
            ])
            ->add('city',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'ville vide'
                    ])
                ]
            ])
            ->add('country',CountryType::class,[
                'placeholder'=> '',//entré vide pour ne pas avoir afganistant par default
                'constraints' => [
                    new NotBlank([
                        'message' => 'pay vide'
                    ])
                ]
            ]);

        /* dans un controlleur on injecte request et tout le reste request stack
         * écouteur : ecouter un seul événement
         * souscripteur : écouter plusieur événement
         * */
        //souscripteur
        $subscriber = new UserTypeSubscriber($this->requestStack);
        $builder->addEventSubscriber($subscriber);


        //écouteur
        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event)//formevent $event plus ou moin egale a l'instance en cours
        {
            //recuperer le nom de la route
                $route = $this ->request->get('_route');


            //tester le route
            //route de création de compte
            if($route === 'account.register')
            {
                /*
                 * l'événement renvoie
                 *         $event->getData() : saisie du formulaire
                 *         $event->getForm() : $builder du formulaire (add)
                 *         $event->getForm()->getData() : données du formulaire (entité, modele...)
                 * */

                //récuperation de la saisie
                $data = $event->getData();

                //formulaire
                $form = $event->getForm();

                //données du formulaire
                $entity = $form->getData();

                // supprimer les champs du formulaire
                $form->remove('address');
                $form->remove('zipcode');
                $form->remove('city');
                $form->remove('country');

            }
        });


    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
