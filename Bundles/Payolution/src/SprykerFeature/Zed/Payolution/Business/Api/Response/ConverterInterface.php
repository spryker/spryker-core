<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Response;

use Generated\Shared\Transfer\PayolutionResponseTransfer;

interface ConverterInterface
{

    /**
     * @param array $data
     *
     * @return PayolutionResponseTransfer
     */
    public function fromArray(array $data);

}
