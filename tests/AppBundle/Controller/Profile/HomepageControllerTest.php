<?php

namespace Tests\AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    /*@dataprovider  :forubnissuer de données
    - doit retournée un array de donneés
    - les entrées du tableau devienne des parametre dans la fonction callback
     * */
//permet de tester l'acces a une page via les id d'un usr
    public function listRoutes()
    {
        return [
            [ '/fr/profile/'],
            [ '/en/profile/']
            ];
    }

    /**
     * @dataProvider  listRoutes
     */
    public function testIndex(string $url)
    {//client : simule un navigateur
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'yolo',
            'PHP_AUTH_PW'=> 'yolo',
        ]);
    // crawler : recupere le dom de l'url ciblé
        $crawler = $client->request('GET', $url);
    //test des route
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

}
