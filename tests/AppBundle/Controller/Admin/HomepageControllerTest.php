<?php

namespace Tests\AppBundle\Controller\Admin;

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
            [ '/fr/admin/', 'Tableau de bord'],
            [ '/en/admin/', 'Tableau de bord']
            ];
    }

    /**
     * @dataProvider  listRoutes
     */
    public function testIndex(string $url, string $title)
    {//client : simule un navigateur
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'admin',
            'PHP_AUTH_PW'=> 'admin',
        ]);
    // crawler : recupere le dom de l'url ciblé
        $crawler = $client->request('GET', $url);
    //test des route
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

}
