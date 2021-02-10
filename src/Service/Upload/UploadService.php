<?php
/**
 * Created by PhpStorm.
 * User: Marshall.D.Teach
 * Date: 26/03/2020
 * Time: 23:43
 */

namespace App\Service\Upload;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class UploadService
{
    public function upload(UploadedFile $file, $safeFileName, $targetDirectory)
    {
        $fileName = $safeFileName.'-'.uniqid().'.'.$file->guessExtension();

        try{
            $file->move($targetDirectory, $fileName);
        }catch (FileException $e){
            return new Response("<h1>Une erreur s'est produite lors de l'envoie... veuillez réessayer !</h1>");
        }

        return $fileName;
    }
}