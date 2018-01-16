<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    /*@dataprovider  :forubnissuer de données
    - doit retournée un array de donneés
    - les entrées du tableau devienne des parametre dans la fonction callback
     * */

    public function listRoutes()
    {
        return [
            [ '/fr/c/categorie0', 'Catégories'],
            [ '/en/c/category0', 'Catégories']
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
    //test des route
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(10,$crawler->filter('h3')->count());
        $this->assertFalse($crawler->filter('h3')->count()===4);
    }
    
}
