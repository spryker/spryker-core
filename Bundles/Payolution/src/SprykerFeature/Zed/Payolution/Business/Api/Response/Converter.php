<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Response;

use Generated\Shared\Payolution\PayolutionResponseInterface;
use Generated\Shared\Transfer\PayolutionResponseTransfer;

class Converter implements ConverterInterface
{

    /**
     * @param array $data
     *
     * @return PayolutionResponseInterface
     */
    public function fromArray(array $data)
    {
        $responseTransfer = new PayolutionResponseTransfer();

        foreach ($data as $key => $value) {
            $convertedKey = str_replace(['_', '.'], ' ', $key);
            $convertedKey = mb_strtolower($convertedKey);
            $convertedKey = ucwords($convertedKey);
            $convertedKey = str_replace(' ', '', $convertedKey);
            $methodName = 'set' . $convertedKey;

            if (!method_exists($responseTransfer, $methodName)) {
                continue;
            }

            $responseTransfer->$methodName($value);
        }

        return $responseTransfer;
    }

}
