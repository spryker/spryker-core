<?php


namespace SprykerFeature\Zed\ProductOptionExporter\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductOptionExporterDependencyContainer getDependencyContainer()
 */
class ProductOptionExporterFacade extends AbstractFacade
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
}
