<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Sluggable\Util\Urlizer;

class UploadHelper
{

    private $_uploadsPath;

    public function __construct($uploadsPath)
    {
        $this->_uploadsPath = $uploadsPath;
    }

    public function UploadFile(UploadedFile $uploadedFile): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename)  . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move(
            $this->_uploadsPath,
            $newFilename
        );
        return $newFilename;
    }
}