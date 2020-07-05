<?php

namespace App\Service;


class EntityServiceAbstract
{
    protected $entityManager;
    protected $repository;


    protected function save()
    {
        $this->entityManager->flush();
    }
}