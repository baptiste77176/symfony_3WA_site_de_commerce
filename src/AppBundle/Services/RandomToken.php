<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 19/01/18
 * Time: 12:06
 */

namespace AppBundle\Services;


class RandomToken
{
    public function generateToken()
    {
        $bytes = random_bytes(5);
       return bin2hex($bytes);

    }

}