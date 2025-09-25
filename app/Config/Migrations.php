<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Migrations extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Enable/Disable Migrations
     * --------------------------------------------------------------------------
     *
     * Migrations are enabled by default.
     *
     * You should enable migrations whenever you intend to do a schema migration
     * and disable it back when you're done.
     */
    public bool $enabled = true;

    /**
     * --------------------------------------------------------------------------
     * Migrations Table
     * --------------------------------------------------------------------------
     *
     * This is the name of the table that will store the current migrations state.
     * When migrations runs it will store in a database table which migration
     * files have already been run.
     */
    public string $table = 'migrations';

    /**
     * --------------------------------------------------------------------------
     * Timestamp Format
     * --------------------------------------------------------------------------
     *
<<<<<<< HEAD
     * Supported formats:
     * - YmdHis_   (e.g., 20250814203055_CreateUsersTable.php)
     * - Y_m_d_His_ (e.g., 2025_08_14_203055_CreateUsersTable.php)
     */
    public string $timestampFormat = 'YmdHis_'; 
=======
     * This is the format that will be used when creating new migrations
     * using the CLI command:
     *   > php spark make:migration
     *
     * NOTE: if you set an unsupported format, migration runner will not find
     *       your migration files.
     *
     * Supported formats:
     * - YmdHis_
     * - Y-m-d-His_
     * - Y_m_d_His_
     */
    public string $timestampFormat = 'Y-m-d-His_';
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
}
