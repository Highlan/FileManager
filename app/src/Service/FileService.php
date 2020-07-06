<?php

namespace App\Service;


use App\Entity\File;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileService extends EntityServiceAbstract
{
    const USER_FILE_UPLOAD_PATH = 'users/files/';

    private $_uploadHelper;

    public function __construct(EntityManagerInterface $entityManager, UploadHelper $uploadHelper)
    {
        $this->entityManager = $entityManager;
        $this->_uploadHelper = $uploadHelper;
    }

    public function create(UploadedFile $uploadedFile, User $user)
    {
        $file = new File($user);
        $file->setSize($uploadedFile->getSize());
        $file->setFormat($uploadedFile->guessExtension());
        $file->setName($this->_uploadHelper->uploadFile($uploadedFile, self::USER_FILE_UPLOAD_PATH . $user->getId(), false));
        $file->setOriginName(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME) ?? $file->getName());

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

    public function download(File $file): BinaryFileResponse
    {
        $response = new BinaryFileResponse($this->_uploadHelper->getUploadPath(FileService::USER_FILE_UPLOAD_PATH . $file->getOwner()->getId() . '/' . $file->getName(), false));
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file->getOriginName() . '.' . $file->getFormat());

        return $response;
    }
}