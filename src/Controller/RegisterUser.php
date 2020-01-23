<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

header("Access-Control-Allow-Origin: *");

class RegisterUser extends AbstractController
{
    public function registerUser(Request $request)
    {
        //$sPAth = $request->getPathInfo();
        $content = json_decode($request->getContent());
        $sFullname = $content->name;
        $sUsername = $content->username;
        $sPassword = password_hash($content->password, PASSWORD_DEFAULT);

        $user = new User();
        $user->setNavn($sFullname);
        $user->setUsername($sUsername);
        $user->setPassword($sPassword);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse("Bruker ble lagret");
    }
}