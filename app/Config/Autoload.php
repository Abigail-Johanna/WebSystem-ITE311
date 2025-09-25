<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
<<<<<<< HEAD
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 *       the values in this file will overwrite the framework's values.
 *
 * NOTE: This class is required prior to Autoloader instantiation,
 *       and does not extend BaseConfig.
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
<<<<<<< HEAD
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The 'Config' (APPPATH . 'Config') and 'CodeIgniter' (SYSTEMPATH) are
     * already mapped for you.
     *
     * You may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * @var array<string, list<string>|string>
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH,
=======
     *
     * Maps namespaces to directories. The 'Config' and 'CodeIgniter'
     * namespaces are already mapped.
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For app/ directory
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    ];

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
<<<<<<< HEAD
     * The class map provides a map of class names and their exact
     * location on the drive. Classes loaded in this manner will have
     * slightly faster performance because they will not have to be
     * searched for within one or more directories as they would if they
     * were being autoloaded through a namespace.
     *
     * Prototype:
     *   $classmap = [
     *       'MyClass'   => '/path/to/class/file.php'
     *   ];
     *
     * @var array<string, string>
=======
     *
     * Map specific class names to their file locations.
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
     */
    public $classmap = [];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
<<<<<<< HEAD
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     *   $files = [
     *       '/path/to/my/file.php',
     *   ];
     *
     * @var list<string>
=======
     *
     * Non-class files to autoload (like helper functions).
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
<<<<<<< HEAD
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var list<string>
     */
    public $helpers = ['url', 'form'];

=======
     *
     * Helpers that will be automatically loaded.
     *
     * url      - for base_url(), site_url()
     * form     - for form_open(), csrf_field()
     * session  - for working with session data
     */
    public $helpers = ['url', 'form', 'session'];
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
}
