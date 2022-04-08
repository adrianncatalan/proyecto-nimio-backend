<?php

namespace App\Controller\Media;

use App\Entity\UserImage;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class CreateUserImageAction extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    public function __invoke(Request $request): UserImage
    {
        $alt = $request->get('alt');
        $owner = $request->get('owner');
        if (!$alt) {
            throw new BadRequestHttpException('"alt" is required');
        }
        if (!$owner) {
            throw new BadRequestHttpException('"owner" is required');
        }
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $user = $this->entityManager->find('App\Entity\User', $owner);

        $userImage = new UserImage();
        $userImage->file = $uploadedFile;
        $userImage-> setAlt($alt);
        $userImage->setOwner($user);

        return $userImage;
    }
}
