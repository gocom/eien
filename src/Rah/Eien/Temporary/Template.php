<?php

interface Rah_Eien_Temporary_Template
{
    /**
     * Initializes the instance's action.
     *
     * Each class only implements one action, and execution
     * is performed automatically at when invoked or destroyed.
     *
     * All other exposed methods should avoid performing any actual
     * changes the to filesystem, and only retrieve information already
     * initialized at the init.
     */

    public function init();
}