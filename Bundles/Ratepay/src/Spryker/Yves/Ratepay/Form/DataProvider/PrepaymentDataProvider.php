<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class PrepaymentDataProvider extends DataProviderAbstract
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $paymentMethodTransfer = new RatepayPaymentPrepaymentTransfer();
            $paymentMethodTransfer->setPhone($this->getPhoneNumber($quoteTransfer));
            $paymentTransfer->setRatepayPrepayment($paymentMethodTransfer);

            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }
}
