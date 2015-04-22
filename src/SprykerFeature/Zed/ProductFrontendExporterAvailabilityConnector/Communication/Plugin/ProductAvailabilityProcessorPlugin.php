<?php

namespace SprykerFeature\Zed\ProductFrontendExporterAvailabilityConnector\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class ProductAvailabilityProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
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
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, $locale)
    {
        foreach ($resultSet as $index => $productRawData) {
            if (isset($processedResultSet[$index])) {
                $processedResultSet[$index]['available'] = ($productRawData['quantity'] > 0);
            }
        }

        return $processedResultSet;
    }
}
