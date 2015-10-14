<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request;

use Generated\Shared\Payolution\PayolutionRequestInterface;

interface ConverterInterface
{

    /**
     * @param PayolutionRequestInterface $requestTransfer
     *
     * @return array
     */
    public function toArray(PayolutionRequestInterface $requestTransfer);

}
