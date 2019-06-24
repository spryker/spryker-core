<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\StorageDatabase;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface StorageDatabaseConstants
{
    /**
     * Specification:
     * - Defines DBMS for storage database.
     *
     * @api
     */
    public const DB_ENGINE = 'STORAGE_DATABASE:DB_ENGINE';

    /**
     * Specification:
     * - Defines host for storage database.
     *
     * @api
     */
    public const DB_HOST = 'STORAGE_DATABASE:DB_HOST';

    /**
     * Specification:
     * - Defines TCP port for accessing storage database.
     *
     * @api
     */
    public const DB_PORT = 'STORAGE_DATABASE:DB_PORT';

    /**
     * Specification:
     * - Defines storage database name.
     *
     * @api
     */
    public const DB_DATABASE = 'STORAGE_DATABASE:DB_DATABASE';

    /**
     * Specification:
     * - Defines username for accessing storage database.
     *
     * @api
     */
    public const DB_USERNAME = 'STORAGE_DATABASE:DB_USERNAME';

    /**
     * Specification:
     * - Defines password for accessing storage database.
     *
     * @api
     */
    public const DB_PASSWORD = 'STORAGE_DATABASE:DB_PASSWORD';

    /**
     * Specification:
     * - Defines debug mode for connection to storage database.
     *
     * @api
     */
    public const DB_DEBUG = 'STORAGE_DATABASE:DB_DEBUG';

    /**
     * Specification:
     * - Defines a map for storage/search table names.
     *
     * Example:
     *
     * $config[StorageDatabaseConstants::RESOURCE_PREFIX_TO_STORAGE_TABLE_MAP] = [
     *     'translation' => [
     *         StorageDatabaseConfig::KEY_STORAGE_TABLE_NAME => 'glossary',
     *     ],
     *     'product_search_config_extension' => [
     *         StorageDatabaseConfig::KEY_STORAGE_TABLE_PREFIX => 'pyz',
     *         StorageDatabaseConfig::KEY_STORAGE_TABLE_NAME => 'product_search_config',
     *         StorageDatabaseConfig::KEY_STORAGE_TABLE_SUFFIX => 'storage',
     *     ],
     * ];
     *
     * @api
     */
    public const RESOURCE_PREFIX_TO_STORAGE_TABLE_MAP = 'STORAGE_DATABASE:RESOURCE_PREFIX_TO_STORAGE_TABLE_MAP';
}
