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
 * Checks file status.
 *
 * @access private
 * @example
 * new Rah_Eien_Action_Stat('/path/to/foobar.txt', 'rd');
 */

class Rah_Eien_Action_Stat
{
    /**
     * Constructor.
     *
     * @param  string $filename The filename
     * @param  string $status   Status flags "r", "w", "f", "d"
     * @throws Rah_Eien_Action_Exception
     */

    public function __construct($filename, $status = 'rwf')
    {
        if (file_exists($filename) === false)
        {
            throw new Rah_Eien_Action_Exception('File does not exists: '.$filename);
        }

        if (strpos($status, 'f') !== false && is_file($filename) === false)
        {
            throw new Rah_Eien_Action_Exception('Specified file is not a file: '.$filename);
        }

        if (strpos($status, 'd') !== false && is_dir($filename) === false)
        {
            throw new Rah_Eien_Action_Exception('Specified file is not a directory: '.$filename);
        }

        if (strpos($status, 'r') !== false && is_readable($filename) === false)
        {
            throw new Rah_Eien_Action_Exception('File is not readable: '.$filename);
        }

        if (strpos($status, 'w') !== false && is_writeable($filename) === false)
        {
            throw new Rah_Eien_Action_Exception('File is not writeable: '.$filename);
        }
    }
}