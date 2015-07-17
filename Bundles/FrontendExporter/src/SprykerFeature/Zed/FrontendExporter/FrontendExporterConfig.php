<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\ExportFailedDeciderPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class FrontendExporterConfig extends AbstractBundleConfig
{

    /**
     * @return DataProcessorPluginInterface[]
     */
    public function getKeyValueProcessors()
    {
        return [];
    }

    /**
     * @return QueryExpanderPluginInterface[]
     */
    public function getKeyValueQueryExpander()
    {
        return [];
    }

    /**
     * @return ExportFailedDeciderPluginInterface[]
     */
    public function getKeyValueExportFailedDeciders()
    {
        return [];
    }

    /**
     * @return ExportFailedDeciderPluginInterface[]
     */
    public function getSearchExportFailedDeciders()
    {
        return [];
    }

    /**
     * @return QueryExpanderPluginInterface[]
     */
    public function getSearchQueryExpander()
    {
        return [];
    }

    /**
     * @return DataProcessorPluginInterface[]
     */
    public function getSearchProcessors()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getSearchUpdateProcessors()
    {
        return [];
    }

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
