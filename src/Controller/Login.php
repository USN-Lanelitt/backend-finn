<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

$request = Request::createFromGlobals();

header("Access-Control-Allow-Origin: *");

class Login extends AbstractController
{
    public function verifyLogin(Request $request)
    {
        //$sPAth = $request->getPathInfo();
        $content = json_decode($request->getContent());
        $sUsername = $content->username;
        $sPassword = $content->password;

        //$conn = $this->getEntityManager()->getConnection();
        $conn = $this->getDoctrine()->getConnection();

        $sql = 'SELECT * FROM user p WHERE p.username = :username';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['username' => $sUsername]);

        // returns an array of arrays (i.e. a raw data set)
        $return = $stmt->fetchAll();

        $db_iId       = "";
        $db_sName     = "";
        $db_sUsername = "";
        $db_sPassword = "";

        if (count($return) > 0)
        {
            $db_iId       = $return[0]['navn'];
            $db_sName     = $return[0]['navn'];
            $db_sUsername = $return[0]['username'];
            $db_sPassword = $return[0]['password'];
        }

        if (password_verify($sPassword, $db_sPassword))
        {
            $sFeedback = "Bruker ". $db_sUsername ." er logget inn. ID: ".$db_iId." Navn: ".$db_sName;
        }
        else
        {
            $sFeedback = "Brukernavn eller passord finnes ikke";
        }

        return new JsonResponse($sFeedback);
    }
}