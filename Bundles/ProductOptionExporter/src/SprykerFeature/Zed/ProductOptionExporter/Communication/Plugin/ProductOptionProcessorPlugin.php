<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionExporter\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductOptionExporter\Business\ProductOptionExporterFacade;
use SprykerFeature\Zed\Collector\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\ProductOptionExporter\Communication\ProductOptionExporterDependencyContainer;

/**
 * @method ProductOptionExporterDependencyContainer getDependencyContainer()
 * @method ProductOptionExporterFacade getFacade()
 */
class ProductOptionProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'abstract_product';
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        return $this->getFacade()->processDataForExport($resultSet, $processedResultSet, $locale);
    }

}
