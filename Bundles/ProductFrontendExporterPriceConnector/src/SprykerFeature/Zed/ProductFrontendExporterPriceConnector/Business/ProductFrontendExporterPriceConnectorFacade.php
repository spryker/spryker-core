<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductFrontendExporterPriceConnectorDependencyContainer getDependencyContainer()
 */
class ProductFrontendExporterPriceConnectorFacade extends AbstractFacade
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     *
     * @return array
     */
    public function processDataForExport(array &$resultSet, array $processedResultSet)
    {
        return $this->getDependencyContainer()->getProcessorModel()->processData($resultSet, $processedResultSet);
    }

    /**
     * @return string
     */
    public function getDefaultPriceType()
    {
        return $this->getDependencyContainer()->getHelperModel()->getDefaultPriceType();
    }

}
