<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request;

use Generated\Shared\Payolution\PayolutionRequestInterface;
use SprykerFeature\Shared\Payolution\PayolutionConfigConstants;

class Converter implements ConverterInterface
{

    /**
     * @param PayolutionRequestInterface $requestTransfer
     *
     * @return array
     */
    public function toArray(PayolutionRequestInterface $requestTransfer)
    {
        $result = [];

        foreach ($requestTransfer->toArray() as $propertyName => $propertyValue) {
            if ($propertyValue === null) {
                continue;
            }

            if ($propertyName === PayolutionConfigConstants::ANALYSIS_CRITERIA) {
                $result = array_merge($this->getAnalysisCriteria($propertyValue), $result);
                continue;
            }

            $result[$this->convertKey($propertyName)] = $propertyValue;
        }

        return $result;
    }

    /**
     * @param array|\ArrayObject $criteria
     *
     * @return array
     */
    private function getAnalysisCriteria($criteria)
    {
        $result = [];
        foreach ($criteria as $criterion) {
            $key = 'CRITERION.' . $criterion['name'];
            $result[$key] = $criterion['value'];
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function convertKey($key)
    {
        $convertedKey = mb_strtoupper(str_replace('_', '.', $key));

        return $convertedKey;
    }

}
