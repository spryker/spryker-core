<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Response;

use SprykerFeature\Zed\Payolution\Business\Exception\ApiResponseInvalidPropertyException;

abstract class AbstractResponse
{

    /**
     * @param $array
     *
     * @throws ApiResponseInvalidPropertyException
     */
    public function initFromArray($array)
    {
        foreach ($array as $key => $value) {
            $wordified = str_replace('_', ' ', $key);
            $lowerCase = strtolower($wordified);
            $nounified = ucwords($lowerCase);
            $camelCase = str_replace(' ', '', $nounified);

            $method = 'set' . $camelCase;

            if (!method_exists($this, $method)) {
                throw new ApiResponseInvalidPropertyException(sprintf('Got unexpected property in response: %s', $key));
            }

            $this->$method($value);
        }
    }

}
