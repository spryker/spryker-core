<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface PreConditionCheckerInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $response
     *
     * @return void
     */
    public function checkPreConditions(CheckoutRequestTransfer $request, CheckoutResponseTransfer $response);

}
