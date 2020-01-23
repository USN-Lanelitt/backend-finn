<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class UserController
{
    /**
     *
     */
    public function getUsers()
    {
        //sleep(2);
        return new JsonResponse(
            array(
                array(
                    "username" => "finn",
                    "password" => "finn12",
                    "fullname" => "Finn Gundersen"
                ),
                array(
                    "username" => "patrik",
                    "password" => "patrik12",
                    "fullname" => "Patrik Lorentzen"
                ),
                array(
                    "username" => "mikael",
                    "password" => "mikael12",
                    "fullname" => "Mikael Rønnevik"
                )
            )
        );
    }
}