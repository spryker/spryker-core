<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;

abstract class DataProviderAbstract implements StepEngineFormDataProviderInterface
{

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $quoteTransfer->setPayment($paymentTransfer);
        }
        $this->setRatepayPaymentTransfer($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected abstract function setRatepayPaymentTransfer(QuoteTransfer $quoteTransfer);

    /**
     * Todo check this method, why public why needed?
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentMethodTransfer
     *
     * @return void
     */
    public function fillPaymentPhoneFromCustomer(TransferInterface $paymentMethodTransfer, QuoteTransfer $quoteTransfer)
    {
        if (empty($paymentMethodTransfer->getPhone)) {
            $paymentMethodTransfer->setPhone($quoteTransfer->requireBillingAddress()->getBillingAddress()->getPhone());
        }
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer|QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [];
    }

}
