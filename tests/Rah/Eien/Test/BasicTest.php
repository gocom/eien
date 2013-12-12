<?php

class Rah_Eien_Test_BasicTest extends PHPUnit_Framework_TestCase
{
    /**
     * Makes sure simple usage case to get the filename works.
     */

    public function testGetPathToTemporaryFile()
    {
        $path = (string) new Rah_Eien_Temporary_File();
        $this->assertTrue($path !== '' && is_dir(dirname($path)));
    }

    /**
     * Gets path to the temporary directory.
     */

    public function testGetPathToTemporaryDirectory()
    {
        $file = new Rah_Eien_Temporary_Directory();
        $path = $file->getFilename();
        $this->assertTrue($path && is_dir($path) && is_writeable($path));

        $path = (string) new Rah_Eien_Temporary_Directory();
        $this->assertTrue($path && !file_exists($path));
    }

    /**
     * Makes sure unsetting removes the temporary file.
     */

    public function testTrashLeakage()
    {
        $directory = new Rah_Eien_Temporary_Directory();

        file_put_contents($directory->getFilename() . '/testFile.txt', 'Test');
        mkdir($directory->getFilename() . '/testDir');
        file_put_contents($directory->getFilename() . '/testDir/testFile.txt', 'Test');
        $directoryPath = (string) $directory;
        unset($directory);
        $this->assertTrue(!file_exists($directoryPath));

        $file = new Rah_Eien_Temporary_File();
        $filePath = (string) $file;
        unset($file);
        $this->assertTrue(!file_exists($filePath));
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

        $this->assertTrue(file_get_contents($final) === 'Test');
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

        $this->assertTrue(file_get_contents($file->getFilename()) === 'Test');
    }

    /**
     * Test making a temporary directory from source.
     */

    public function testMakingDirectory()
    {
        // Create test source directory.
        $sourceDir = new Rah_Eien_Temporary_Directory();
        $source = $sourceDir->getFilename();

        file_put_contents($source . '/file1.txt', 'Test');
        mkdir($source . '/testDir');
        file_put_contents($source . '/testDir/file2.txt', 'Test');

        // Create temporary directory from the files.
        $tmp = new Rah_Eien_File();
        $tmp->file($source);
        $file = new Rah_Eien_Temporary_Directory($tmp);
        $tmp = $file->getFilename();

        $this->assertTrue(file_exists($tmp . '/file1.txt') && file_exists($tmp . '/testDir/file2.txt'));
    }
}