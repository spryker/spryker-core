<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector;

use Spryker\Shared\Collector\CollectorConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Collector\Persistence\Pdo\PostgreSql\BulkDeleteTouchByIdQuery;
use Spryker\Zed\Collector\Persistence\Pdo\PostgreSql\BulkUpdateTouchKeyByIdQuery;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CollectorConfig extends AbstractBundleConfig
{

    const COLLECTOR_TOUCH_ID = 'collector_touch_id';
    const COLLECTOR_RESOURCE_ID = 'collector_resource_id';
    const COLLECTOR_STORAGE_KEY = 'collector_storage_key';
    const COLLECTOR_SEARCH_KEY = 'collector_search_key';

    const COLLECTOR_TYPE_PRODUCT_ABSTRACT = 'product_abstract';
    const COLLECTOR_TYPE_CATEGORYNODE = 'categorynode';
    const COLLECTOR_TYPE_NAVIGATION = 'navigation';
    const COLLECTOR_TYPE_TRANSLATION = 'translation';
    const COLLECTOR_TYPE_PAGE = 'page';
    const COLLECTOR_TYPE_BLOCK = 'block';
    const COLLECTOR_TYPE_REDIRECT = 'redirect';
    const COLLECTOR_TYPE_URL = 'url';

    const COLLECTOR_BULK_DELETE_QUERY_CLASS = 'BulkDeleteTouchByIdQuery';
    const COLLECTOR_BULK_UPDATE_QUERY_CLASS = 'BulkUpdateTouchKeyByIdQuery';

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
    public function getPgsqlEngineName()
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
    public function getAvailableCollectorTypes()
    {
        return [
            self::COLLECTOR_TYPE_PRODUCT_ABSTRACT,
            self::COLLECTOR_TYPE_CATEGORYNODE,
            self::COLLECTOR_TYPE_NAVIGATION,
            self::COLLECTOR_TYPE_TRANSLATION,
            self::COLLECTOR_TYPE_PAGE,
            self::COLLECTOR_TYPE_BLOCK,
            self::COLLECTOR_TYPE_REDIRECT,
            self::COLLECTOR_TYPE_URL,
        ];
    }

    /**
     * @return int
     */
    public function getNumberOfShards()
    {
        return 4;
    }

    /**
     * @return int
     */
    public function getNumberOfReplicas()
    {
        return 1;
    }

    /**
     * @return array
     */
    protected function getQueryToDbEngineClassMap()
    {
        return [
            $this->getMysqlEngineName() => [

            ],
            $this->getPgsqlEngineName() => [
                static::COLLECTOR_BULK_DELETE_QUERY_CLASS => BulkDeleteTouchByIdQuery::class,
                static::COLLECTOR_BULK_UPDATE_QUERY_CLASS => BulkUpdateTouchKeyByIdQuery::class
            ]
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

}
