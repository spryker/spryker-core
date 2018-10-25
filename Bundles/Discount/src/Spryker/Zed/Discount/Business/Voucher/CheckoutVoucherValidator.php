<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Voucher;

use Generated\Shared\Transfer\CheckoutResponseTransfer;

class CheckoutVoucherValidator extends VoucherValidator
{
    /**
     * @var \Spryker\Zed\Discount\Business\Checkout\CheckoutResponseTransferTrayInterface
     */
    protected $messengerFacade;

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function getCheckoutResponseTransfer(): CheckoutResponseTransfer
    {
        return $this->messengerFacade->getCheckoutResponseTransfer();
    }
}
