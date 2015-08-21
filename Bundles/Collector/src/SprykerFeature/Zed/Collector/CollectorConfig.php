<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector;

use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CollectorConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getSearchIndexName()
    {
        return Config::get(SystemConfig::ELASTICA_PARAMETER__INDEX_NAME);
    }

    /**
     * @return string
     */
    public function getSearchDocumentType()
    {
        return Config::get(SystemConfig::ELASTICA_PARAMETER__DOCUMENT_TYPE);
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
