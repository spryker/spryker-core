<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeInterface;

class QuotePaymentExpander implements QuotePaymentExpanderInterface
{
    /**
     * @var \Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeInterface
     */
    protected PaymentAppToPaymentFacadeInterface $paymentFacade;

    /**
     * @param \Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeInterface $paymentFacade
     */
    public function __construct(PaymentAppToPaymentFacadeInterface $paymentFacade)
    {
        $this->paymentFacade = $paymentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithPayment(
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $paymentTransfer = $this->paymentFacade->expandPaymentWithPaymentSelection(
            $quoteTransfer->getPayments()->offsetGet(0),
            $quoteTransfer->getStoreOrFail(),
        );

        $quoteTransfer
            ->setPayment($paymentTransfer)
            ->setPayments(new ArrayObject([$paymentTransfer]));

        return $quoteTransfer;
    }
}
