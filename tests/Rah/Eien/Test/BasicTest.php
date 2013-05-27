<?php

class Rah_Eien_Test_BasicTest extends PHPUnit_Framework_TestCase
{
    /**
     * Makes sure simple usage case to get the filename works.
     */

    public function testGetPathToTemporaryFile()
    {
        $path = (string) new Rah_Eien_Temporary_File();
        return $path && is_writeable($path);
    }

    /**
     * Gets path to the temporary directory.
     */

    public function testGetPathToTemporaryDirectory()
    {
        $path = (string) new Rah_Eien_Temporary_Directory();
        return $path && is_writeable($path);
    }

    /**
     * Makes sure unsetting removes the temporary file.
     */

    public function testTrashLeakage()
    {
        $directory = new Rah_Eien_Temporary_Directory();
        $file = new Rah_Eien_Temporary_File();
        $filePath = (string) $file;
        $directoryPath = (string) $directory;
        unset($file, $directory);
        return !file_exists($filePath) && !file_exists($directoryPath);
    }

    /**
     * Make sure moving works correctly.
     */

    public function testMoving()
    {
        $final = (string) new Rah_Eien_Temporary_File();

        $tmp = new Rah_Eien_File();
        $tmp->final($final);

        $file = new Rah_Eien_Temporary_File($tmp);
        file_put_contents($file->getFilename(), 'Test');
        $file->move();

        return file_get_contents($final) === 'Test';
    }

    /**
     * Test making a temporary file from source.
     */

    public function testMakingFile()
    {
        $source = (string) new Rah_Eien_Temporary_File();
        file_put_contents($source, 'Test');

        $tmp = new Rah_Eien_File();
        $tmp->file($source);
        $file = new Rah_Eien_Temporary_File($tmp);
        unlink($source);

        return file_get_contents($file->getFilename()) === 'Test';
    }
}