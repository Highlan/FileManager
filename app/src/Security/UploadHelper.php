<?php

namespace App\Security;


class UploadHelper
{

    private $_uploadPath;

    public function __construct($uploadPath)
    {
        $this->_uploadPath = $uploadPath;
    }
}