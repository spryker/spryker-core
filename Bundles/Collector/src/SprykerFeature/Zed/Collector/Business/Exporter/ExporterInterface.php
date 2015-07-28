<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\Collector\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\Collector\Dependency\Plugin\QueryExpanderPluginInterface;

interface ExporterInterface
{

    /**
     * @param DataProcessorPluginInterface $processor
     */
    public function addDataProcessor(DataProcessorPluginInterface $processor);

    /**
     * @param QueryExpanderPluginInterface $queryExpander
     */
    public function addQueryExpander(QueryExpanderPluginInterface $queryExpander);

    /**
     * @param string $type
     * @param LocaleTransfer $locale
     *
     * @return BatchResultInterface
     */
    public function exportByType($type, LocaleTransfer $locale);

}
