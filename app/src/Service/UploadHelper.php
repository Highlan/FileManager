<?php

namespace App\Service;


use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Sluggable\Util\Urlizer;

class UploadHelper
{

    private $_publicUploadsPath;
    private $_privateUploadsPath;
    private $_requestStackContext;


    public function __construct($publicUploadsPath, $privateUploadsPath, RequestStackContext $requestStackContext)
    {
        $this->_publicUploadsPath = $publicUploadsPath;
        $this->_privateUploadsPath = $privateUploadsPath;
        $this->_requestStackContext = $requestStackContext;
    }

    public function UploadFile(File $file, $path, $isPublic): string
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }

        $newFilename = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move(
                ($isPublic ? $this->_publicUploadsPath : $this->_privateUploadsPath) . $path,
                $newFilename
            );
        } catch (FileException $e) {
            // todo
        }


        return $newFilename;
    }

    public function getPublicPath(string $path): string
    {
        return $this->_requestStackContext->getBasePath() . 'uploads/' . $path;
    }

    public function DeleteFile()
    {

    }
}