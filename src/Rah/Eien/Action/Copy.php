<?php

/*
 * Eien - Temporary file handler
 * https://github.com/gocom/eien
 *
 * Copyright (C) 2013 Jukka Svahn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * Copies a file.
 *
 * @access private
 * @example
 * new Rah_Eien_Action_Copy('/path/to/source.txt', '/path/to/target.txt');
 */

class Rah_Eien_Action_Copy
{
    /**
     * Constructor.
     *
     * @param  string $source
     * @param  string $target
     * @throws Rah_Eien_Action_Exception
     */

    public function __construct($source, $target)
    {
        new Rah_Eien_Action_Stat($source, 'r');

        if (is_dir($source))
        {
            $this->copyDirectory($source, $target);
        }
        else
        {
            $this->copyFile($source, $target);
        }
    }

    /**
     * Copies a file.
     *
     * @param  string $source
     * @param  string $target
     * @throws Rah_Eien_Action_Exception
     */

    protected function copyFile($source, $target)
    {
        new Rah_Eien_Action_Stat($source, 'rf');

        if (file_exists($target))
        {
            new Rah_Eien_Action_Stat($target, 'wf');
        }

        if ($in = fopen($source, 'rb'))
        {
            if ($out = fopen($target, 'wb'))
            {
                flock($out, LOCK_EX);
                flock($in, LOCK_EX);

                while (!feof($in))
                {
                    fwrite($out, fread($in, 512));
                }

                flock($in, LOCK_UN);
                flock($out, LOCK_UN);
                fclose($out);
            }

            fclose($in);
        }
        else
        {
            throw new Rah_Eien_Action_Exception('Unable to copy "'.$source.'" to "'.$target.'".');
        }
    }

    /**
     * Copies a directory.
     *
     * @param  string $source
     * @param  string $target
     * @throws Rah_Eien_Action_Exception
     */

    protected function copyDirectory($source, $target)
    {
        new Rah_Eien_Action_Stat($source, 'rd');
        new Rah_Eien_Action_Stat($target, 'wd');

        if (($cwd = getcwd()) === false || chdir($target) === false)
        {
            throw new Rah_Eien_Action_Exception('Unable to change the current working directory for writing.');
        }

        $files = new RecursiveDirectoryIterator($source);
        $file = new RecursiveIteratorIterator($files, RecursiveIteratorIterator::SELF_FIRST);

        while ($file->valid())
        {
            if ($file->isDot() === false)
            {
                $name = $file->getSubPathName();

                if ($file->isDir())
                {
                    if (@mkdir($name) === false)
                    {
                        throw new Rah_Eien_Exception('Unable to create a directory: '.$name);
                    }
                }
                else
                {
                    $this->copyFile($file->getPathname(), $name);
                }
            }

            $file->next();
        }

        if (chdir($cwd) === false)
        {
            throw new Rah_Eien_Action_Exception('Unable to restore the current working directory.');
        }
    }
}