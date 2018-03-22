<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DummyPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;

class DummyPaymentInvoiceFormDataProvider
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions($quoteTransfer)
    {
        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData($quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $paymentTransfer->setDummyPayment(new DummyPaymentTransfer());
            $paymentTransfer->setDummyPaymentInvoice(new DummyPaymentTransfer());

            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }
}
