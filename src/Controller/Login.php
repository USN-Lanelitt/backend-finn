<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

//$request = Request::createFromGlobals();

header("Access-Control-Allow-Origin: *");

class Login extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function verifyLogin(Request $request)
    {
        $this->logger->info($request);

        $content = json_decode($request->getContent());
        $sUsername = $content->username;
        $sPassword = $content->password;

        //$conn = $this->getEntityManager()->getConnection();
        $conn = $this->getDoctrine()->getConnection();

        // hente ut brukerinfo
        $sql = 'SELECT * FROM user p WHERE p.username=:username';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['username' => $sUsername]);

        // returns an array of arrays (i.e. a raw data set)
        $aReturn['user'] = $stmt->fetchAll();

        if (count(aReturn['user']) > 0)
        {
            $db_iIndividId = aReturn['user'][0]['individ_id'];
            $db_sPassword  = aReturn['user'][0]['password'];

            // hente ut individinfo utifra individ_id fra brukertabellen
            $sql = 'SELECT * FROM individuals p WHERE p.id=:id';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $db_iIndividId]);

            // returns an array of arrays (i.e. a raw data set)
            $aReturn['individ'] = $stmt->fetchAll();
        }

        if (! password_verify($sPassword, $db_sPassword))
        {
            $aReturn = array();
        }

        return new JsonResponse($aReturn);
    }
}