<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentsRestApi\Business\Quote;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentTransfer;
use Spryker\Zed\PaymentsRestApi\Dependency\Facade\PaymentsRestApiToPaymentFacadeInterface;

class PaymentQuoteMapper implements PaymentQuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\PaymentsRestApi\Dependency\Facade\PaymentsRestApiToPaymentFacadeInterface
     */
    protected PaymentsRestApiToPaymentFacadeInterface $paymentFacade;

    /**
     * @param \Spryker\Zed\PaymentsRestApi\Dependency\Facade\PaymentsRestApiToPaymentFacadeInterface $paymentFacade
     */
    public function __construct(PaymentsRestApiToPaymentFacadeInterface $paymentFacade)
    {
        $this->paymentFacade = $paymentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapPaymentsToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $quoteTransfer = $this->mapPaymentTransfersToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        return $this->mapPreOrderPaymentDataToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapPaymentTransfersToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $restPaymentTransfers = $restCheckoutRequestAttributesTransfer->getPayments();

        $quoteTransfer->setPreOrderPaymentData($restCheckoutRequestAttributesTransfer->getPreOrderPaymentData());

        if (!$restPaymentTransfers->count()) {
            return $quoteTransfer;
        }

        $paymentTransfer = $this->preparePaymentTransfer($restPaymentTransfers->offsetGet(0));

        $paymentTransfer = $this->paymentFacade->expandPaymentWithPaymentSelection($paymentTransfer, $quoteTransfer->getStore());

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapPreOrderPaymentDataToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $quoteTransfer->setPreOrderPaymentData($restCheckoutRequestAttributesTransfer->getPreOrderPaymentData());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestPaymentTransfer $restPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function preparePaymentTransfer(RestPaymentTransfer $restPaymentTransfer): PaymentTransfer
    {
        $paymentTransfer = (new PaymentTransfer())->fromArray($restPaymentTransfer->toArray(), true);

        $paymentTransfer
            ->setPaymentProvider($restPaymentTransfer->getPaymentProviderName())
            ->setPaymentProviderName($restPaymentTransfer->getPaymentProviderName())
            ->setPaymentMethodName($restPaymentTransfer->getPaymentMethodName())
            ->setPaymentMethod($restPaymentTransfer->getPaymentMethodName());

        return $paymentTransfer;
    }
}
