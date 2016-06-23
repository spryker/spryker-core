<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;

class ElvDataProvider extends DataProviderAbstract
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setRatepayPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment()->getRatepayElv() === null) {
            $quoteTransfer->getPayment()->setRatepayElv(new RatepayPaymentElvTransfer());
        }
        $this->fillPaymentPhoneFromCustomer($quoteTransfer->getPayment()->getRatepayElv(), $quoteTransfer);
    }

}
