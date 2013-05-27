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
 * Creates a new temporary directory instance.
 */

class Rah_Eien_Temporary_Directory extends Rah_Eien_Base implements Rah_Eien_Temporary_Template
{
    /**
     * {@intheritdoc}
     */

    public function init()
    {
        $this->tmpDirectory();

        if ($this->config->file)
        {
            if (!file_exists($this->config->file) || !is_dir($this->config->file))
            {
                throw new Rah_Eien_Temporary_Exception('Source is not readable directory.');
            }

            new Rah_Eien_Action_Copy($this->config->file, $this->temp);
        }
    }

    /**
     * {@inheritdoc}
     */

    protected function clean()
    {
        if ($this->temp !== null && file_exists($this->temp))
        {
            if (is_file($this->temp))
            {
                parent::clean();
            }
            else if (is_dir($this->temp))
            {
                $files = new RecursiveDirectoryIterator($this->temp);
                $file = new RecursiveIteratorIterator($files, RecursiveIteratorIterator::CHILD_FIRST);

                while ($file->valid())
                {
                    if (!$file->isDot())
                    {
                        if ($file->isDir())
                        {
                            if (rmdir($file->getPathname()) === false)
                            {
                                throw new Rah_Eien_Exception('Unable to remove the temporary trash.');
                            }
                        }
                        else
                        {
                            if (unlink($file->getPathname()) === false)
                            {
                                throw new Rah_Eien_Exception('Unable to remove the temporary trash.');
                            }
                        }
                    }

                    $file->next();
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */

    public function move()
    {
        if ($this->temp === null || $this->config->final === null)
        {
            throw new Rah_Eien_Exception('No file to move specified.');
        }

        try
        {
            new Rah_Eien_Action_Stat($this->temp, 'rd');
            new Rah_Eien_Action_Stat($this->config->final, 'wd');
            new Rah_Eien_Action_Copy($this->temp, $this->config->final);
        }
        catch (Exception $e)
        {
            throw new Rah_Eien_Exception('Unable to move the temporary directory: '.$e->getMessage());
        }

        $this->trash();
        return $this;
    }
}