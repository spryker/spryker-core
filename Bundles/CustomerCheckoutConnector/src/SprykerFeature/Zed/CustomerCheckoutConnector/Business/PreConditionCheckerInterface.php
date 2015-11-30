<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface PreConditionCheckerInterface
{

    /**
     * @param CheckoutRequestTransfer $request
     * @param CheckoutResponseTransfer $response
     */
    public function checkPreConditions(CheckoutRequestTransfer $request, CheckoutResponseTransfer $response);

}
