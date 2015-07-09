<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
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
        return $this->getDependencyContainer()
            ->getPriceProcessor()
            ->processDataForExport($resultSet, $processedResultSet)
            ;
    }

}
