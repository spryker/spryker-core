<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Response;

use Generated\Shared\Transfer\PayolutionResponseTransfer;
use SprykerFeature\Zed\Payolution\Business\Exception\ApiResponseConverterInvalidPropertyException;

class Converter implements ConverterInterface
{

    /**
     * @param array $data
     *
     * @return PayolutionResponseTransfer
     */
    public function fromArray(array $data)
    {
        $responseTransfer = new PayolutionResponseTransfer();

        foreach ($data as $key => $value) {
            $convertedKey = str_replace(['_', '.'], ' ', $key);
            $convertedKey = strtolower($convertedKey);
            $convertedKey = ucwords($convertedKey);
            $convertedKey = str_replace(' ', '', $convertedKey);
            $methodName = 'set' . $convertedKey;

            if (!method_exists($responseTransfer, $methodName)) {
                throw new ApiResponseConverterInvalidPropertyException(sprintf(
                    'Got unknown property "%s"',
                    $key
                ));
            }

            $responseTransfer->$methodName($value);
        }

        return $responseTransfer;
    }

}
