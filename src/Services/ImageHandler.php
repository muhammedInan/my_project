<?php

namespace App\Services;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageHandler
{
    private $path;
    
    public function __construct($path)
    {
        $this->path = $path.'/public/images';
    }

    
}