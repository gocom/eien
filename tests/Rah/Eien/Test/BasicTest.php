<?php

class Rah_Eien_Test_BasicTest extends PHPUnit_Framework_TestCase
{
    public function testGetPathToTemporaryFile()
    {
        $path = (string) new Rah_Eien_Temporary_File();
        $this->assertTrue($path !== '');
        $this->assertFileExists(dirname($path));
    }

    public function testGetPathToTemporaryDirectory()
    {
        $file = new Rah_Eien_Temporary_Directory();
        $path = $file->getFilename();
        $this->assertTrue($path && is_dir($path) && is_writeable($path));
    }

    public function testDirectoryDestructor()
    {
        $path = (string) new Rah_Eien_Temporary_Directory();
        $this->assertTrue($path !== '');
        $this->assertTrue(!file_exists($path));
    }

    public function testFileDestructor()
    {
        $file = new Rah_Eien_Temporary_File();
        $path = (string) $file;
        unset($file);
        $this->assertTrue(!file_exists($path));
    }

    public function testRecursiveDirectoryDestructor()
    {
        $directory = new Rah_Eien_Temporary_Directory();
        $path = (string) $directory;

        $this->assertFileExists($path);
        $this->assertTrue(is_dir($path), $path);

        mkdir($path . '/directory1');
        mkdir($path . '/directory2');
        file_put_contents($path . '/file.txt', 'Test');
        file_put_contents($path . '/directory1/file.txt', 'Test');

        $this->assertFileExists($path . '/file.txt');
        $this->assertFileExists($path . '/directory1/file.txt');

        unset($directory);
        clearstatcache();
        $this->assertTrue(!file_exists($path), $path);
    }

    public function testFileFinalMoving()
    {
        $final = (string) new Rah_Eien_Temporary_File();

        $tmp = new Rah_Eien_File();
        $tmp->final($final);

        $file = new Rah_Eien_Temporary_File($tmp);
        file_put_contents($file->getFilename(), 'Test');
        $file->move();

        $this->assertFileExists($final);
        $this->assertEquals('Test', file_get_contents($final), $final);
    }

    public function testMakingFile()
    {
        $source = new Rah_Eien_Temporary_File();
        file_put_contents((string) $source, 'Test');

        $tmp = new Rah_Eien_File();
        $tmp->file((string) $source);
        $file = new Rah_Eien_Temporary_File($tmp);

        $this->assertFileExists((string) $file);
        $this->assertFileEquals((string) $source, (string) $file);
    }

    public function testMakingDirectory()
    {
        $source = new Rah_Eien_Temporary_Directory();

        mkdir((string) $source . '/directory');
        file_put_contents((string) $source . '/file.txt', 'Test');
        file_put_contents((string) $source . '/directory/file.txt', 'Test');

        $this->assertFileExists((string) $source . '/directory');
        $this->assertFileExists((string) $source . '/file.txt');
        $this->assertFileExists((string) $source . '/directory/file.txt');

        $tmp = new Rah_Eien_File();
        $tmp->file((string) $source);
        $file = new Rah_Eien_Temporary_Directory($tmp);

        $this->assertFileExists((string) $file);
        $this->assertFileEquals((string) $source . '/directory/file.txt', (string) $file . '/directory/file.txt');
    }
}
