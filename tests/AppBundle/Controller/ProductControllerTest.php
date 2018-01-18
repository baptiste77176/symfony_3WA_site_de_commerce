<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    /*@dataprovider  :fournnisseur de données
    - doit retournée un array de donneés
    - les entrées du tableau devienne des parametre dans la fonction callback
     * */

    public function listRoutes()
    {
        return [
            [ '/fr/p/categorie0/produit0', 'Product'],
            [ '/en/p/category0/product0', 'Product']
            ];
    }

    /**
     * @dataProvider  listRoutes
     */
    public function testIndex(string $url, string $title)
    {//client : simule un navigateur
        $client = static::createClient();
    // crawler : recupere le dom de l'url ciblé
        $crawler = $client->request('GET', $url);
    //test des routes
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($title,$crawler->filter('.container > .row:nth-of-type(3) h1')->text());

    }
    
}
