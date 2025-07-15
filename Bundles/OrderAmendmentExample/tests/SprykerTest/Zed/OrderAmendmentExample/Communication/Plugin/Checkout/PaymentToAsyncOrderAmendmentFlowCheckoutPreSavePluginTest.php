<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OrderAmendmentExample\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\OrderAmendmentExample\Communication\Plugin\Checkout\PaymentToAsyncOrderAmendmentFlowCheckoutPreSavePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OrderAmendmentExample
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group PaymentToAsyncOrderAmendmentFlowCheckoutPreSavePluginTest
 * Add your own group annotations below this line
 */
class PaymentToAsyncOrderAmendmentFlowCheckoutPreSavePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PAYMENT_METHOD_INVOICE = 'dummyPaymentInvoice';

    /**
     * @var string
     */
    protected const QUOTE_PROCESS_FLOW_NAME_1 = 'flow1';

    /**
     * @uses \Spryker\Zed\OrderAmendmentExample\Business\Processor\OrderAmendmentCheckoutProcessor::ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE
     *
     * @var string
     */
    protected const ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE = 'order amendment draft applied';

    /**
     * @return void
     */
    public function testPreSaveShouldHydrateQuoteWithQuoteProcessFlowAndShouldSkipStateMachineRunProperty(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName(static::QUOTE_PROCESS_FLOW_NAME_1))
            ->setPayment((new PaymentTransfer())->setPaymentSelection(static::PAYMENT_METHOD_INVOICE));

        // Act
        $quoteTransfer = (new PaymentToAsyncOrderAmendmentFlowCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertTrue($quoteTransfer->getShouldSkipStateMachineRun());
        $this->assertSame(
            SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT_ASYNC,
            $quoteTransfer->getQuoteProcessFlow()->getName(),
        );
    }

    /**
     * @return void
     */
    public function testPreSaveShouldNotHydrateQuoteWhenDefaultOmsOrderItemStateIsSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setDefaultOmsOrderItemState(static::ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE)
            ->setQuoteProcessFlow(
                (new QuoteProcessFlowTransfer())->setName(static::QUOTE_PROCESS_FLOW_NAME_1),
            )->setPayment((new PaymentTransfer())->setPaymentSelection(static::PAYMENT_METHOD_INVOICE));

        // Act
        $quoteTransfer = (new PaymentToAsyncOrderAmendmentFlowCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertSame(static::QUOTE_PROCESS_FLOW_NAME_1, $quoteTransfer->getQuoteProcessFlow()->getName());
    }

    /**
     * @return void
     */
    public function testPreSaveShouldNotHydrateQuoteWhenPaymentSelectionIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName(static::QUOTE_PROCESS_FLOW_NAME_1))
            ->setPayment(new PaymentTransfer());

        // Act
        $quoteTransfer = (new PaymentToAsyncOrderAmendmentFlowCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertSame(static::QUOTE_PROCESS_FLOW_NAME_1, $quoteTransfer->getQuoteProcessFlow()->getName());
    }

    /**
     * @return void
     */
    public function testPreSaveShouldNotHydrateQuoteWhenPaymentMethodIsNotInvoice(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName(static::QUOTE_PROCESS_FLOW_NAME_1))
            ->setPayment((new PaymentTransfer())->setPaymentSelection('unknownPaymentMethod'));

        // Act
        $quoteTransfer = (new PaymentToAsyncOrderAmendmentFlowCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertSame(static::QUOTE_PROCESS_FLOW_NAME_1, $quoteTransfer->getQuoteProcessFlow()->getName());
    }
}
