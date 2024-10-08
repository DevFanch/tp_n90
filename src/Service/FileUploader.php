<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {

        // Filename generation
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            // Move the file to the directory
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // Throw exception if any error
            throw new FileException($e->getMessage());
        }

        return $fileName;
    }

    public function delete(string $fileName): void
    {
        if (file_exists($this->getTargetDirectory().'/'.$fileName)) {
            unlink($this->getTargetDirectory().'/'.$fileName);
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}