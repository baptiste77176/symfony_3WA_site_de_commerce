<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    /*@dataprovider  :forubnissuer de données
    - doit retournée un array de donneés
    - les entrées du tableau devienne des parametre dans la fonction callback
     * */

    public function listRoutes()
    {
        return [
            [ '/fr/', 'Catégories'],
            [ '/en/', 'Catégories']
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
        $this->assertContains($title, $crawler->filter('.container > .row:nth-of-type(2)')->text());
        //compter le nombre de boutons categories
        $this->assertGreaterThan(0,$crawler->filter('.container > .row:nth-of-type(2) .btn')->count());
        //compter les 3 produit aléatoires
        $this->assertEquals(3,$crawler->filter('.container > .row:nth-of-type(3) .col-sm-4')->count());
        $this->assertFalse($crawler->filter('.container > .row:nth-of-type(3) .col-sm-4')->count()===4);
    }

}
