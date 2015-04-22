<?php

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Communication\ProductFrontendExporterPriceConnectorDependencyContainer;

/**
 * @method ProductFrontendExporterPriceConnectorDependencyContainer getDependencyContainer()
 */
class ProductPriceProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'product';
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param string $locale
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, $locale)
    {
        return $this->getDependencyContainer()
            ->getPriceProcessor()
            ->processDataForExport($resultSet, $processedResultSet)
            ;
    }
}
