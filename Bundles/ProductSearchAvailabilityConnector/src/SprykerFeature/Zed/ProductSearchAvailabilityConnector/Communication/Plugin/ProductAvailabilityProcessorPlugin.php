<?php

namespace SprykerFeature\Zed\ProductSearchAvailabilityConnector\Communication\Plugin;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;

class ProductAvailabilityProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
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
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleDto $locale)
    {

        foreach ($resultSet as $index => $productRawData) {
            if (isset($processedResultSet[$index])) {
                $isAvailable = (bool)(
                    $productRawData['is_never_out_of_stock'] ||
                    $productRawData['quantity'] > 0
                );
                $processedResultSet[$index]['search-result-data']['available'] = $isAvailable;
                $processedResultSet[$index]['bool-facet']['available'] = $isAvailable;
            }
        }

        return $processedResultSet;
    }
}
