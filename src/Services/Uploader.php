<?php


namespace App\Services;


use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $directory;
    private $slugger;
    private $fileSystem;

    public function __construct(string $targetDirectory, Slugger $slugger, Filesystem $fileSystem)
    {
        $this->slugger = $slugger;
        $this->fileSystem = $fileSystem;
        $this->directory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileExtension = $file->getExtension();
        $safeFileName = $this->slugger->slug($fileName) . '-' . uniqid();

        try
        {
            $file->move($this->directory, $safeFileName . '.' . $fileExtension);
        } catch (FileException $error)
        {
            //Gérer l'erreur ...
            //envoyer un message flash
        }
        return [
            'filename' => $safeFileName,
            'extension' => $fileExtension,
        ];
    }

    public function remove(string $filePath)
    {
        try {
            $this->fileSystem->remove($filePath);
        } catch(IOException $exception)
        {
            //Générer un message flash
        }
    }
}