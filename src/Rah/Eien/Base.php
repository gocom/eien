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
 * The base class.
 */

abstract class Rah_Eien_Base implements Rah_Eien_Template
{
    /**
     * The temporary filename.
     *
     * @var string
     */

    protected $temp;

    /**
     * The config.
     *
     * @var Rah_Eien_Config
     */

    protected $config;

    /**
     * Whether the directory has been cleaned or not.
     *
     * @var bool
     */

    protected $clean = false;

    /**
     * {@inheritdoc}
     */

    public function __construct(Rah_Eien_Config_Values $config = null)
    {
        if ($config === null)
        {
            $this->config = new Rah_Eien_File();
        }
        else
        {
            $this->config = $config;
        }

        $this->findTmpDirectory();
        $this->init();
    }

    /**
     * {@inheritdoc}
     */

    public function __destruct()
    {
        if ($this->clean === false && $this->config->final !== null)
        {
            $this->move();
        }

        $this->trash();
    }

    /**
     * {@inheritdoc}
     */

    public function __toString()
    {
        return $this->getFilename();
    }

    /**
     * {@inheritdoc}
     */

    public function getFilename()
    {
        return (string) $this->temp;
    }

    /**
     * {@inheritdoc}
     */

    public function findTmpDirectory()
    {
        if ($this->config->tmp !== null)
        {
            if (!is_writeable($this->config->tmp))
            {
                throw new Rah_Eien_Exception('Temporary directory "'.$this->config->tmp.'" is not writeable.');
            }

            return $this->config->tmp;
        }

        if (($directory = ini_get('upload_tmp_dir')) && is_writeable($directory))
        {
            $this->config->tmp = $directory;
            return $directory;
        }

        if (function_exists('sys_get_temp_dir'))
        {
            if (($directory = sys_get_temp_dir()) && is_writeable($directory) && $directory = realpath($directory))
            {
                $this->config->tmp = $directory;
                return $directory;
            }
        }

        throw new Rah_Eien_Exception('Unable to find the temporary directory.');
    }

    /**
     * Gets a path to a temporary file.
     *
     * @throws Rah_Eien_Exception
     */

    protected function tmpFile()
    {
        if (($this->temp = tempnam($this->config->tmp, $this->config->prefix)) === false)
        {
            throw new Rah_Eien_Exception('Unable to create a temporary file, check the configured tmp directory.');
        }

        if ($this->config->extension)
        {
            if (rename($this->temp, $this->temp.'.'.$this->config->extension) === false)
            {
                throw new Rah_Eien_Exception('Unable to add "'.$this->config->extension.'" extension to "'.$this->temp.'".');
            }

            $this->temp .= '.'.$this->config->extension;
        }

        $this->clean();
    }

    /**
     * Gets a path to a temporary directory.
     *
     * @throws Rah_Eien_Exception
     */

    protected function tmpDirectory()
    {
        $this->tmpFile();

        if (mkdir($this->temp) === false)
        {
            throw new Rah_Eien_Exception('Unable to create a temporary directory, check the configured tmp directory.');
        }
    }

    /**
     * Clean temporary trash.
     *
     * @throws Rah_Eien_Exception
     */

    protected function clean()
    {
        if ($this->temp && file_exists($this->temp))
        {
            if (unlink($this->temp) === false)
            {
                $this->clean = false;
                throw new Rah_Eien_Exception('Unable to remove the temporary trash.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */

    public function trash()
    {
        if ($this->clean === false)
        {
            $this->clean = true;
            $this->clean();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */

    public function move()
    {
        if ($this->clean === true || !$this->temp || $this->config->final === null)
        {
            throw new Rah_Eien_Exception('No file to move specified, or temporary trash cleared already.');
        }

        if (@rename($this->temp, $this->config->final) === false)
        {
            try
            {
                new Rah_Eien_Action_Copy($this->temp, $this->config->final);
            }
            catch (Exception $e)
            {
                throw new Rah_Eien_Exception('Unable to move the temporary file: '.$e->getMessage());
            }

            $this->trash();
        }

        $this->clean = true;
        return $this;
    }
}