<?php

namespace App\Controller;

use App\Entity\Individuals;
use App\Entity\User;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use \Datetime;

header("Access-Control-Allow-Origin: *");

class RegisterUser extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function registerUser(Request $request)
    {
        $this->logger->info($request);
        //$sPAth = $request->getPathInfo();

        $content = json_decode($request->getContent());
        $sFirstname  = $content->firstname;
        $sMiddlename = ""; //$content->middlename;
        $sLastname   = $content->lastname;
        //$sBirthdate  = DateTime::createFromFormat('Y.m.d', $content->birthday);
        $sBirthdate  = DateTime::createFromFormat('Y-m-d', $content->birthday);
        $sEmail      = $content->email;
        $sMobile     = $content->phone;
        $sPassword   = password_hash($content->password, PASSWORD_DEFAULT);

        //$sFullname = $content->name;
        //$sUsername = $content->username;
        //$sBirthdate = DateTime::createFromFormat('d.m.Y', $content->birthday);
        //$sBirthdate = $content->birthday;
        //$sBirthdate = DateTime::createFromFormat($sBirthdate, 'Y-m-d');
        //$sBirthdate = self::convert_date_nor2sql($sBirthdate);
        //$sBirthdate2 = Datetime::createFromFormat('d.m.Y', $sBirthdate);
        //$sPassword = password_hash($content->password, PASSWORD_DEFAULT);
        //$sUsername = $content->username;

        // legge til individ i databasen
        $oIndividuals = new Individuals();
        $oIndividuals->setFirstname($sFirstname);
        $oIndividuals->setMiddlename($sMiddlename);
        $oIndividuals->setLastname($sLastname);
        $oIndividuals->setBirthdate($sBirthdate);
        $oIndividuals->setEmail($sEmail);
        $oIndividuals->setMobile($sMobile);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($oIndividuals);
        $entityManager->flush();

        $iIndividId = $oIndividuals->getId();

        // legge inn bruker
        $oUsers = new Users();
        $oUsers->setIndividId($iIndividId);
        $oUsers->setPassword($sPassword);
        $oUsers->setRights("1111111111");
        $oUsers->setUsertype('USER');
        $oUsers->setActive('T');
        $oUsers->setNewsSubscription('F');
        $oUsers->setGdpr('F');
        $oUsers->setUserterms('F');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($oUsers);
        $entityManager->flush();

        return new JsonResponse("Bruker ble opprettet");
    }
}
