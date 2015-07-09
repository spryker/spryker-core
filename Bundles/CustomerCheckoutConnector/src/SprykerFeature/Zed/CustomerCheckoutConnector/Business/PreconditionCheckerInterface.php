<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\CustomerCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface PreconditionCheckerInterface
{

    /**
     * @param CheckoutRequestInterface $request
     * @param CheckoutResponseTransfer $response
     */
    public function checkPreconditions(CheckoutRequestInterface $request, CheckoutResponseTransfer $response);

}
