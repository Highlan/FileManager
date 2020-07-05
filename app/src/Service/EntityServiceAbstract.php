<?php

namespace AppBundle\Service;


class EntityServiceAbstract
{
    protected $entityManager;
    protected $repository;


    protected function save()
    {
        $this->entityManager->flush();
    }
}