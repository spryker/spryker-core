<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector;

use Spryker\Shared\Collector\CollectorConstants;
use Spryker\Shared\Config\Config;
use Spryker\Zed\Collector\Persistence\Pdo\MySql\BulkDeleteTouchByIdQuery as MySqlBulkDeleteTouchByIdQuery;
use Spryker\Zed\Collector\Persistence\Pdo\MySql\BulkUpdateTouchKeyByIdQuery as MySqlBulkUpdateTouchKeyByIdQuery;
use Spryker\Zed\Collector\Persistence\Pdo\PostgreSql\BulkDeleteTouchByIdQuery as PostgreSqlBulkDeleteTouchByIdQuery;
use Spryker\Zed\Collector\Persistence\Pdo\PostgreSql\BulkUpdateTouchKeyByIdQuery as PostgreSqlBulkUpdateTouchKeyByIdQuery;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CollectorConfig extends AbstractBundleConfig
{
    public const COLLECTOR_TOUCH_ID = 'collector_touch_id';
    public const COLLECTOR_RESOURCE_ID = 'collector_resource_id';
    public const COLLECTOR_STORAGE_KEY = 'collector_storage_key';
    public const COLLECTOR_SEARCH_KEY = 'collector_search_key';

    public const COLLECTOR_BULK_DELETE_QUERY_CLASS = 'BulkDeleteTouchByIdQuery';
    public const COLLECTOR_BULK_UPDATE_QUERY_CLASS = 'BulkUpdateTouchKeyByIdQuery';

    /**
     * @return string
     */
    public function getSearchIndexName()
    {
        return Config::get(CollectorConstants::ELASTICA_PARAMETER__INDEX_NAME);
    }

    /**
     * @return string
     */
    public function getSearchDocumentType()
    {
        return Config::get(CollectorConstants::ELASTICA_PARAMETER__DOCUMENT_TYPE);
    }

    /**
     * @return string
     */
    public function getMysqlEngineName()
    {
        return Config::get(CollectorConstants::ZED_DB_ENGINE_MYSQL);
    }

    /**
     * @return string
     */
    public function getPostgresEngineName()
    {
        return Config::get(CollectorConstants::ZED_DB_ENGINE_PGSQL);
    }

    /**
     * @return string
     */
    public function getCurrentEngineName()
    {
        return Config::get(CollectorConstants::ZED_DB_ENGINE);
    }

    /**
     * @return int
     */
    public function getStandardChunkSize()
    {
        return 1000;
    }

    /**
     * @return array
     */
    public function getChunkSizeTypeMap()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getQueryToDbEngineClassMap()
    {
        return [
            $this->getMysqlEngineName() => $this->getMysqlDbEngineClassMap(),
            $this->getPostgresEngineName() => $this->getPostgresDbEngineClassMap(),
        ];
    }

    /**
     * @return array
     */
    protected function getMysqlDbEngineClassMap()
    {
        return [
            static::COLLECTOR_BULK_DELETE_QUERY_CLASS => MySqlBulkDeleteTouchByIdQuery::class,
            static::COLLECTOR_BULK_UPDATE_QUERY_CLASS => MySqlBulkUpdateTouchKeyByIdQuery::class,
        ];
    }

    /**
     * @return array
     */
    protected function getPostgresDbEngineClassMap()
    {
        return [
            static::COLLECTOR_BULK_DELETE_QUERY_CLASS => PostgreSqlBulkDeleteTouchByIdQuery::class,
            static::COLLECTOR_BULK_UPDATE_QUERY_CLASS => PostgreSqlBulkUpdateTouchKeyByIdQuery::class,
        ];
    }

    /**
     * @return array
     */
    public function getCurrentBulkQueryClassNames()
    {
        $classMap = $this->getQueryToDbEngineClassMap();

        return $classMap[$this->getCurrentEngineName()];
    }

    /**
     * @return bool
     */
    public function getEnablePrepareScopeKeyJoinFixFeatureFlag()
    {
        return false;
    }
}
