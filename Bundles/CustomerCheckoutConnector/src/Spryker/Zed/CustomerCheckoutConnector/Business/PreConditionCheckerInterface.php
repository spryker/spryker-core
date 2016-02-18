<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
