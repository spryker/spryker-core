<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request;

use Generated\Shared\Transfer\PayolutionRequestTransfer;

class Converter implements ConverterInterface
{

    /**
     * @param PayolutionRequestTransfer $requestTransfer
     *
     * @return array
     */
    public function toArray(PayolutionRequestTransfer $requestTransfer)
    {
        $result = [];

        foreach ($requestTransfer->toArray() as $propertyName => $propertyValue) {
            if (null === $propertyValue) {
                continue;
            }

            $key = str_replace('_', '.', $propertyName);
            $key = mb_strtoupper($key);
            $result[$key] = $propertyValue;
        }

        return $result;
    }

}
