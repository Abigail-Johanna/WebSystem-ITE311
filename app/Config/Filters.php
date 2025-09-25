<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
<<<<<<< HEAD
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    // Aliases make filters easier to use in routes or globally
    public array $aliases = [
        'csrf'          => CSRF::class,
=======
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthCheck; // 🔹 Import our custom filter

/**
 * -------------------------------------------------------------------
 * FILTERS CONFIGURATION
 * -------------------------------------------------------------------
 */
class Filters extends BaseFilters
{
    /**
     * -------------------------------------------------------------------
     * Aliases for Filter classes
     * -------------------------------------------------------------------
     */
    public array $aliases = [
        // 'csrf'          => CSRF::class, // Temporarily disabled
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
<<<<<<< HEAD
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
    ];

    // Required filters are always applied (before or after)
=======

        // 🔹 Custom filter to check if user is logged in
        'auth'          => AuthCheck::class,
    ];

    /**
     * -------------------------------------------------------------------
     * Special required filters
     * -------------------------------------------------------------------
     */
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    public array $required = [
        'before' => [],
        'after'  => [],
    ];

<<<<<<< HEAD
    // Global filters (apply to every request)
    public array $globals = [
        'before' => [
            // enable CSRF protection on all POST requests
            'csrf',
        ],
        'after' => [
            // show debug toolbar only in development
            'toolbar',
        ],
    ];

    // Filters by HTTP method (optional)
    public array $methods = [];

    // Filters by URI pattern (optional)
    public array $filters = [];
=======
    /**
     * -------------------------------------------------------------------
     * Global filters applied to every request
     * -------------------------------------------------------------------
     */
    public array $globals = [
        'before' => [
            // CSRF protection moved to method-based filters
        ],
        'after' => [
            'toolbar', // Debug toolbar enabled
        ],
    ];

    /**
     * -------------------------------------------------------------------
     * Method-based filters
     * -------------------------------------------------------------------
     *
     * Example:
     * 'POST' => ['csrf', 'foo']
     */
    public array $methods = [
        // Temporarily disabled CSRF to test dashboard access
        // 'POST' => ['csrf'], // Only apply CSRF to POST requests
    ];

    /**
     * -------------------------------------------------------------------
     * URI pattern-based filters
     * -------------------------------------------------------------------
     */
    public array $filters = [
        // 🔹 Only logged-in users can access dashboard
        'auth' => ['before' => ['/dashboard', '/dashboard/*']],
    ];
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
}
