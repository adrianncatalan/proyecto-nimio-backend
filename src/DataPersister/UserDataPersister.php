<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = [])
    {
        $conditionUserPost = $data instanceof User && (($context['collection_operation_name'] ?? null) === 'post');
        $conditionUserPatch = $data instanceof User && (($context['item_operation_name'] ?? null) === 'patch');
        $conditionUserPut = $data instanceof User && (($context['item_operation_name'] ?? null) === 'put');

        if ($conditionUserPost) {
            $this->hashPassword($data);
            // $data->setEmailVerify(false);
        }

        if ($conditionUserPatch || $conditionUserPut) {
            $userPassword = $context['previous_data']->getPassword();
            $passwordRecived = $data->getPassword();
            if( $userPassword != $passwordRecived ){
               $this->hashPassword($data); 
            }
        }

        $this->entityManager->persist($data, $context);
        $this->entityManager->flush();
        
        //$this->registerVerify->sendVerificationEmail($data);
    
    }

    public function hashPassword(User $data) {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $data,
            $data->getPassword()
        );
        $data->setPassword($hashedPassword);
    }
    
    public function remove($data, array $context = [])
    {
        $conditionUserDelete = $data instanceof User && (($context['item_operation_name'] ?? null) === 'delete');

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

}