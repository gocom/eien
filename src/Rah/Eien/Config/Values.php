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
 * The configuration values.
 *
 * @example
 * class MyAppConfig extends Rah_Eien_Config
 * {
 *     public $tmp = '/tmp';
 *     public $ext = '.gz';
 * }
 */

abstract class Rah_Eien_Config_Values
{
    /**
     * The filename.
     *
     * This is used when creating a temporary file from
     * an existing file. Takes a path to the source file.
     *
     * @var string
     */

    public $file;

    /**
     * Path to the final target location for the temporary file.
     *
     * The temporary file is moved to this location
     * once you are done with it.
     *
     * @var string
     */

    public $final;

    /**
     * Path to the temporary directory.
     *
     * @var string
     */

    public $tmp;

    /**
     * Desired file extension added to the created temporary file.
     *
     * @var string
     */

    public $extension;

    /**
     * Desired prefix added to the created temporary file.
     *
     * @var string
     */

    public $prefix = 'Rah_Eien_';
}