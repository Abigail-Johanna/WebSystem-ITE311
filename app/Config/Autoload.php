<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 */
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     *
     * Maps namespaces to directories. The 'Config' and 'CodeIgniter'
     * namespaces are already mapped.
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For app/ directory
    ];

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     *
     * Map specific class names to their file locations.
     */
    public $classmap = [];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     *
     * Non-class files to autoload (like helper functions).
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     *
     * Helpers that will be automatically loaded.
     *
     * url      - for base_url(), site_url()
     * form     - for form_open(), csrf_field()
     * session  - for working with session data
     */
    public $helpers = ['url', 'form', 'session'];
}
