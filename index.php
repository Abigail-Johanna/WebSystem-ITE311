<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
<<<<<<< HEAD
 * and fires up an environment-specific bootstrapping.
=======
 * and fires up an environment-specific bootstrapping.\
 * 
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
// Path to the Paths config file
$pathsConfig = FCPATH . 'app/Config/Paths.php';
require $pathsConfig;

// Load Composer autoload
<<<<<<< HEAD
require FCPATH . 'vendor/autoload.php';

=======
$autoloadPath = FCPATH . 'vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require $autoloadPath;
} else {
    exit('Composer autoload not found. Please run "composer install".');
}
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d

// ^^^ Change this line if you move your application folder

$paths = new Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
