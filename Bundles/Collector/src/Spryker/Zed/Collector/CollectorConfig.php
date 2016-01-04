<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector;

use Spryker\Shared\Collector\CollectorConstants;
use Spryker\Shared\Config;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CollectorConfig extends AbstractBundleConfig
{

    const COLLECTOR_TOUCH_ID = 'collector_touch_id';
    const COLLECTOR_RESOURCE_ID = 'collector_resource_id';
    const COLLECTOR_STORAGE_KEY = 'collector_storage_key';
    const COLLECTOR_SEARCH_KEY = 'collector_search_key';

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

}
