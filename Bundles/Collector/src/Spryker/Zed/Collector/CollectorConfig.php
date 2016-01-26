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
