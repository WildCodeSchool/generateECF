<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 28/06/18
 * Time: 21:41
 */

namespace AppBundle\Services;

use AppBundle\Entity\Promo;

class Zip
{
    private $ecfDirectory;
    private $zipDirectory;

    public function __construct($zipDirectory, $ecfDirectory)
    {
        $this->ecfDirectory = $ecfDirectory;
        $this->zipDirectory = $zipDirectory;
    }

    public function zipFolder(Promo $promo){
        $zip = new \ZipArchive();
        $outputFileName = str_replace(' ', '_', $promo->getName()) . '_ECF.zip';


        if (!file_exists($this->zipDirectory)) {
            mkdir($this->zipDirectory, 0777, true);
        }
        $zip->open($this->zipDirectory . $outputFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // Initialize empty "delete list"
        $filesToDelete = array();

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(

            new \RecursiveDirectoryIterator($this->ecfDirectory . str_replace(' ', '_', $promo->getName())),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($this->ecfDirectory) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();

        // Delete all files from "delete list"
        foreach ($filesToDelete as $file)
        {
            unlink($file);
        }
        rmdir($this->ecfDirectory . str_replace(' ', '_', $promo->getName()));

        return ['filename' => $outputFileName, 'path_to_zip' => $this->zipDirectory . $outputFileName];
    }
}