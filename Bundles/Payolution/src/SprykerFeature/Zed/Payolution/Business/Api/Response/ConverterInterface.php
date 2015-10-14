<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Response;

use Generated\Shared\Payolution\PayolutionResponseInterface;

interface ConverterInterface
{

    /**
     * @param array $data
     *
     * @return PayolutionResponseInterface
     */
    public function fromArray(array $data);

}
