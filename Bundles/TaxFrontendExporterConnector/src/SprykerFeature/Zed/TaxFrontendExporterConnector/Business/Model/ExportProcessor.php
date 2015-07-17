<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Business\Model;

class ExportProcessor implements ExportProcessorInterface
{

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
                if (isset($productRawData['tax_set_name'], $productRawData['tax_rate_names'], $productRawData['tax_rate_rates'])) {
                    $processedResultSet = $this->prepareTaxForResult($processedResultSet, $productRawData, $index);
                } else {
                    // @TODO Check if we do not want to index products which do not have a tax set
                    //unset($processedResultSet[$index]);
                }
            }
        }

        return $processedResultSet;
    }

    /**
     * @param array $processedResultSet
     * @param array $productRawData
     * @param string $index
     *
     * @return array
     */
    protected function prepareTaxForResult(array $processedResultSet, array $productRawData, $index)
    {
        $taxRates = [];
        $taxRateNames = explode(',', $productRawData['tax_rate_names']);
        $taxRateRates = explode(',', $productRawData['tax_rate_rates']);

        $effectiveRate = 0;

        foreach ($taxRateRates as $key => $taxRateRate) {
            $effectiveRate += $taxRateRate;
            $taxRates[] = [
                'name' => $taxRateNames[$key],
                'rate' => (float) $taxRateRate,
            ];
        }

        $processedResultSet[$index]['tax'] = [
            'name' => $productRawData['tax_set_name'],
            'effectiv_rate' => $effectiveRate,
            'rates' => $taxRates,
        ];

        return $processedResultSet;
    }

}
