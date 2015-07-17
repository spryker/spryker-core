<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business\Model;

class ExportProcessor implements ExportProcessorInterface
{

    /**
     * @var HelperInterface
     */
    protected $helper;

    /**
     * @param HelperInterface $helper
     */
    public function __construct(HelperInterface $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet)
    {
        foreach ($resultSet as $index => $productRawData) {
            if (isset($processedResultSet[$index])) {
                if ($productRawData['concrete_prices'] !== null) {
                    $processedResultSet = $this->preparePriceForResult($processedResultSet, $productRawData, $index);
                } else {
                    unset($processedResultSet[$index]);
                }
            }
        }

        return $processedResultSet;
    }

    /**
     * @param array $processedResultSet
     * @param array $productRawData
     * @param int $index
     *
     * @return array
     */
    protected function preparePriceForResult(array $processedResultSet, $productRawData, $index)
    {
        if ($this->helper->hasDefaultPrice($productRawData)) {
            $defaultPrice = $this->helper->getDefaultPrice($productRawData);
            $processedResultSet[$index]['valid_price'] = $defaultPrice;
            $processedResultSet[$index]['prices'] = $this->helper->organizeData($productRawData);

            return $processedResultSet;
        }
        unset($processedResultSet[$index]);

        return $processedResultSet;
    }

}
