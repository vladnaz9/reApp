<?php

namespace App\Service;

use App\Entity\EmployeeFile;
use App\Repository\EmployeeFileRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class EmployeeFileService
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function updateFile(UploadedFile $employeeFile)
    {
        $originalFilename = pathinfo($employeeFile->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $employeeFile->guessExtension();

        $oldFileNames = array_diff(scandir(EmployeeFile::FILE_PATH), array('..', '.'));
        try {
            $employeeFile->move(
                EmployeeFile::FILE_PATH,
                $newFilename
            );
        } catch (FileException $e) {
            return null;
        }

        foreach ($oldFileNames as $oldFileName) {
            unlink(EmployeeFile::FILE_PATH . '/' . $oldFileName);
        }

        return $newFilename;
    }
}