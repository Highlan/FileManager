<?php

namespace App\Service;


class UploadHelper
{

    private $_uploadsPath;

    public function __construct($uploadsPath)
    {
        $this->_uploadsPath = $uploadsPath;
    }
}