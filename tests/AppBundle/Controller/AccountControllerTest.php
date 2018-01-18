<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    /*@dataprovider  :fournnisseur de données
    - doit retournée un array de donneés
    - les entrées du tableau devienne des parametre dans la fonction callback
     * */

    public function listRoutes()
    {
        //route a tester
        return [
            [ '/fr/register', "S'enregistrer"],
            [ '/en/register', 'Signup']
            ];
    }

    /**
     * @dataProvider  listRoutes
     */
    public function testIndex(string $url, string $title)
    {//client : simule un navigateur
        $client = static::createClient();


    // suivre toute les redirections
    $client->followRedirects();

    // crawler : recupere le dom de l'url ciblé
        $crawler = $client->request('GET', $url);


        /*
         * données du formulaire
         *      - array associatif:
         *              clé : name du champ de saisie
         *              valeur : valeur saisie
         * */
//valeur = valeur du name pour ciblage
        $formData = [
            'appbundle_user[username]' => 'user'.time(),
            'appbundle_user[password]' => 'pass'.time(),
            'appbundle_user[email]' => 'email'.time().'@gmail.com',
        ];

        //selectionner le formulaire via le bouton submit/* pour selectionner un formulaire avec phpunit il faut cibler le bouton*/

        // selectionner par le bouton submit et remplir le formulaire

        $form = $crawler->selectButton('Valider')->form($formData);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($title,$crawler->filter('h1')->text());

        //soumission du formulaire // equivalent persist et flush
        // soumission du formulaire : METTRE A JOUR LE DOM
        $crawler = $client->submit($form);
       // dump($crawler);

        // test sur la page d'attérrissage
        $this->assertEquals(1, $crawler->filter('.alert.alert-success')->count());



    }
    
}
