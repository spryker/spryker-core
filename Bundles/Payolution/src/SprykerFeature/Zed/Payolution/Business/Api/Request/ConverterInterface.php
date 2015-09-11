<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request;

use Generated\Shared\Transfer\PayolutionRequestTransfer;

interface ConverterInterface
{

    /**
     * @param PayolutionRequestTransfer $requestTransfer
     *
     * @return mixed
     */
    public function toArray(PayolutionRequestTransfer $requestTransfer);

}
