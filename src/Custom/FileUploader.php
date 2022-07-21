<?php


namespace App\Custom;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function upload(callable $onComplete, string $path, UploadedFile ...$files): void
    {
        if (!file_exists($path)) {
            if (!mkdir($path) && !is_dir($path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
            }
        }

        foreach ($files as $file) {
            $newFilename = uniqid('', false) . '.' . $file->guessExtension();
            try {
                $this->removeFile($path . '/' . $newFilename);
                $file->move($path, $newFilename);
                $onComplete($newFilename, $file->getClientOriginalName());
            } catch (FileException $e) {
                // todo log
            }
        }
    }

    public function removeFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
