<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface CheckoutResponseTransferTrayInterface
{
    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function getCheckoutResponseTransfer(): CheckoutResponseTransfer;
}
