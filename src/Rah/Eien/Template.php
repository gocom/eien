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
 * Base interface.
 */

interface Rah_Eien_Template
{
    /**
     * Constructor.
     *
     * @param  Rah_Eien_Config_Values $config
     * @throws Rah_Eien_Exception
     */

    public function __construct(Rah_Eien_Config_Values $config = null);

    /**
     * Destructor.
     */

    public function __destruct();

    /**
     * Returns a path to the temporary file.
     *
     * @return string
     */

    public function __toString();

    /**
     * Returns a path to the temporary file.
     *
     * @return string
     */

    public function getFilename();

    /**
     * Finds the default temporary directory.
     *
     * This method tries to find the default
     * temporary directory by looking at
     * PHP's configuration values and for system's
     * default temporary directory.
     *
     * You should configure this tmp location yourself,
     * and not blindly trust some value returned
     * by the method. It might be a writeable directory,
     * but not necessarily one where you should be writing.
     *
     * @return string Path to the temporary directory
     * @throws Rah_Eien_Exception
     */

    public function findTmpDirectory();

    /**
     * Trashes the temporary file, and closes the temporary file instance.
     *
     * If you need to get rid of the temporary file instance and discard
     * the file, call this method.
     *
     * @return Rah_Eien_Template
     * @throws Rah_Eien_Exception
     */

    public function trash();

    /**
     * Moves a temporary file to its final location.
     *
     * This method first tries renaming. If that fails
     * due to permissions, it tries copying and
     * after which is removes the left over file.
     *
     * @return Rah_Eien_Template
     * @throws Rah_Eien_Exception
     */

    public function move();
}