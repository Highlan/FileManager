<?php

namespace App\Service;


use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Sluggable\Util\Urlizer;

class UploadHelper
{

    private $_uploadsPath;
    private $_requestStackContext;


    public function __construct($uploadsPath, RequestStackContext $requestStackContext)
    {
        $this->_uploadsPath = $uploadsPath;
        $this->_requestStackContext = $requestStackContext;
    }

    public function UploadFile(UploadedFile $uploadedFile, $path): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename)  . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move(
            $this->_uploadsPath . $path,
            $newFilename
        );
        return $newFilename;
    }

    public function getPublicPath(string $path): string
    {
        return $this->_requestStackContext->getBasePath() . 'uploads/' . $path;
    }
}