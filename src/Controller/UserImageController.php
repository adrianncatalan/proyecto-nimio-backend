<?php

namespace App\Controller;

use App\Entity\UserImage;
use App\Repository\UserImageRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserImageController extends AbstractController
{
    public function __invoke(array $context = [], Request $request): BinaryFileResponse|Response
    {
        dd($context);
        $hash = $request->query->get('exp');

        if(!$hash){
            return new Response('Unauthoriced',401);
        }
        try {
            if($this->verifyHash($hash)){
                $ruta = getcwd()."\media\/" ;
                $file = $userImageRepository->showNameImage($fileId);
            }else{
                return new Response('Unauthoriced',401);
            }
            if(!$file){
                return new Response('FileNotFound',404);
            }
        } catch (Exception $e) {
            echo 'Excepción: ',  $e->getMessage(), "\n";
        }

        return new BinaryFileResponse($ruta.$file['filePath']);
    }

    function verifyHash($hash) {
        if(!strpos($hash, ':')) return false;
        list ($expire, $rawhash) = explode(':', $hash, 2);
        $payload = "";
        $testhash = hash_hmac('sha1', $payload, $expire);
        if ($expire > time() && $testhash == $rawhash) {
            return true;
        }
        return false;
    }

}
