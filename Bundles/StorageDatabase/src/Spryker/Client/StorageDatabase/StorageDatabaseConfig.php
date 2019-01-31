<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\StorageDatabaseConstants;

class StorageDatabaseConfig extends AbstractBundleConfig
{
    protected const MESSAGE_CREDENTIALS_NOT_FOUND_EXCEPTION = 'Credentials not found';

    protected const RESOURCE_TO_STORAGE_TABLE_MAPPING = [
        'translation' => 'glossary',
        'product_search_config_extension' => 'product_search_config',
        'product_abstract_product_lists' => 'product_abstract_product_list',
        'product_concrete_product_lists' => 'product_concrete_product_list',
    ];

    protected const STORAGE_TABLE_PREFIX = 'spy';

    protected const STORAGE_TABLE_SUFFIX = 'storage';

    /**
     * @return array
     */
    public function getConnectionConfig(): array
    {
        $dbEngine = $this->getDatabaseEngine();
        $connections = $this->get(StorageDatabaseConstants::STORAGE_DATABASE_CONNECTION, []);

        if (empty($connections[$dbEngine]) || !is_array($connections[$dbEngine])) {
            // throw new exception
        }

        return $connections[$dbEngine];
    }

    /**
     * @return string
     */
    public function getDatabaseEngine(): string
    {
        return $this->get(StorageDatabaseConstants::STORAGE_DB_ENGINE, '');
    }

    /**
     * @param string $resourceName
     *
     * @return string
     */
    public function getStorageTableNameByResourceName(string $resourceName): string
    {
        $tableName = static::RESOURCE_TO_STORAGE_TABLE_MAPPING[$resourceName] ?? $resourceName;

        return implode('_', [
            static::STORAGE_TABLE_PREFIX,
            $tableName,
            static::STORAGE_TABLE_SUFFIX,
        ]);
    }
}
