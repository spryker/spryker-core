<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Communication\TaxFrontendExporterConnectorDependencyContainer;

/**
 * @method TaxFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class TaxProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
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
            ->getTaxProcessor()
            ->processDataForExport($resultSet, $processedResultSet)
        ;
    }

}
