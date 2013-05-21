<?php

class Rah_Eien_Test_BasicTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testGetPathToTemporaryFile()
    {
        $path = (string) new Rah_Eien_Temporary_File();
        return $path && is_writeable($path);
    }

    public function testGetPathToTemporaryDirectory()
    {
        $path = (string) new Rah_Eien_Temporary_Directory();
        return $path && is_writeable($path);
    }

    public function testTrashLeakage()
    {
        $directory = new Rah_Eien_Temporary_Directory();
        $file = new Rah_Eien_Temporary_File();
        $filePath = (string) $file;
        $directoryPath = (string) $directory;
        unset($file, $directory);
        return !file_exists($filePath) && !file_exists($directoryPath);
    }

    public function tearDown()
    {
        unlink($this->temp);
    }
}