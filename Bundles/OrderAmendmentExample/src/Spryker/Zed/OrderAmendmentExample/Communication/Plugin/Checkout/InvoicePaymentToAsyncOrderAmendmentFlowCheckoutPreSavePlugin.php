<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderAmendmentExample\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class InvoicePaymentToAsyncOrderAmendmentFlowCheckoutPreSavePlugin extends AbstractPlugin implements CheckoutPreSavePluginInterface
{
    /**
     * @uses \Spryker\Shared\DummyPayment\DummyPaymentConfig::PAYMENT_METHOD_INVOICE
     *
     * @var string
     */
    protected const PAYMENT_METHOD_INVOICE = 'dummyPaymentInvoice';

    /**
     * @uses \Spryker\Zed\OrderAmendmentExample\Business\Processor\OrderAmendmentCheckoutProcessor::ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE
     *
     * @var string
     */
    protected const ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE = 'order amendment draft applied';

    /**
     * {@inheritDoc}
     * - Does nothing if `QuoteTransfer.defaultOmsOrderItemState` is equal to `order amendment draft applied`.
     * - Does nothing if `QuoteTransfer.payment.paymentSelection` is not equal to `dummyPaymentInvoice`.
     * - Sets `QuoteTransfer.quoteProcessFlow` to a new `QuoteProcessFlowTransfer` with the name 'order-amendment-async'.
     * - Sets `QuoteTransfer.shouldSkipStateMachineRun` to `true`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preSave(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getDefaultOmsOrderItemState() === static::ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE) {
            return $quoteTransfer;
        }

        $paymentMethod = $quoteTransfer->getPayment()?->getPaymentSelection();
        if ($paymentMethod !== static::PAYMENT_METHOD_INVOICE) {
            return $quoteTransfer;
        }

        $quoteTransfer->setQuoteProcessFlow(
            (new QuoteProcessFlowTransfer())
                ->setName(SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT_ASYNC),
        )->setShouldSkipStateMachineRun(true);

        return $quoteTransfer;
    }
}
