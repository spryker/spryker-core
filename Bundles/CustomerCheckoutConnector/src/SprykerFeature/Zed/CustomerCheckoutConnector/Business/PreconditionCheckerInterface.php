<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface PreconditionCheckerInterface
{

    /**
     * @param CheckoutRequestTransfer $request
     * @param CheckoutResponseTransfer $response
     */
    public function checkPreconditions(CheckoutRequestTransfer $request, CheckoutResponseTransfer $response);

}
