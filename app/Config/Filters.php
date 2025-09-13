<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
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
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,

        // 🔹 Custom filter to check if user is logged in
        'auth'          => AuthCheck::class,
    ];

    /**
     * -------------------------------------------------------------------
     * Special required filters
     * -------------------------------------------------------------------
     */
    public array $required = [
        'before' => [],
        'after'  => [],
    ];

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
}
