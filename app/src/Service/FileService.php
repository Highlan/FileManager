<?php

namespace App\Service;


use App\Entity\File;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
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

    public function update($id, $name): Response
    {
        /** @var File $file */
        $file = $this->entityManager->find(File::class, $id);
        if (!$file){
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $file->setOriginName($name);
//        $this->entityManager->merge($file);
        $this->save();
        return new Response(null, Response::HTTP_OK);
    }

    public function remove(File $file): bool
    {
        $this->entityManager->remove($file);

        try{
            $this->_uploadHelper->deleteFile(self::getDefaultPath($file), false);
            $this->save();
            return true;
        }catch (\Exception $exception){
            return false;
        }
    }

    public function download(File $file): BinaryFileResponse
    {
        $response = new BinaryFileResponse($this->_uploadHelper->getUploadPath(self::getDefaultPath($file), false));
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file->getOriginName() . '.' . $file->getFormat());

        return $response;
    }

    private static function getDefaultPath(File $file): string
    {
        return self::USER_FILE_UPLOAD_PATH . $file->getOwner()->getId() . '/' . $file->getName();
    }
}