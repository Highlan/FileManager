<?php

namespace App\Service;


use App\Entity\File;
use AppBundle\Service\EntityServiceAbstract;
use Doctrine\ORM\EntityManagerInterface;

class FileService extends EntityServiceAbstract
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(File $file)
    {
        $this->entityManager->persist($file);
        $this->save();
    }

    public function update(File $file)
    {
        $this->entityManager->merge($file);
        $this->save();
    }

    public function remove(File $file)
    {
        $this->entityManager->remove($file);
        $this->save();
    }
}