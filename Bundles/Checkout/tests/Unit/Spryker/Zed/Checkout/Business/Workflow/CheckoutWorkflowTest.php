<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Checkout\Business\Workflow;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Checkout\Business\Workflow\CheckoutWorkflow;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;
use Unit\Spryker\Zed\Checkout\Business\Fixture\MockOrderSaver;
use Unit\Spryker\Zed\Checkout\Business\Fixture\MockPostHook;
use Unit\Spryker\Zed\Checkout\Business\Fixture\ResponseManipulatorPreCondition;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Checkout
 * @group Business
 * @group Workflow
 * @group CheckoutWorkflowTest
 */
class CheckoutWorkflowTest extends Test
{

    /**
     * @return void
     */
    public function testWorkflowCallsAllPreConditions()
    {
        $mock1 = $this->getMock(CheckoutPreConditionInterface::class);
        $mock2 = $this->getMock(CheckoutPreConditionInterface::class);

        $mock1->expects($this->once())->method('checkCondition')->with(
            $this->isInstanceOf(QuoteTransfer::class),
            $this->isInstanceOf(CheckoutResponseTransfer::class)
        );

        $mock2->expects($this->once())->method('checkCondition')->with(
            $this->isInstanceOf(QuoteTransfer::class),
            $this->isInstanceOf(CheckoutResponseTransfer::class)
        );

        $omsMock = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Facade\\CheckoutToOmsInterface');
        $checkoutWorkflow = new CheckoutWorkflow([$mock1, $mock2], [], [], $omsMock);

        $quoteTransfer = new QuoteTransfer();
        $checkoutWorkflow->placeOrder($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testWorkflowCallsAllOrderSavers()
    {
        $mock1 = $this->getMock(CheckoutSaveOrderInterface::class);
        $mock2 = $this->getMock(CheckoutSaveOrderInterface::class);

        $quoteTransfer = new QuoteTransfer();

        $mock1->expects($this->once())->method('saveOrder')->with(
            $this->isInstanceOf(QuoteTransfer::class),
            $this->isInstanceOf(CheckoutResponseTransfer::class)
        );

        $mock2->expects($this->once())->method('saveOrder')->with(
            $this->isInstanceOf(QuoteTransfer::class),
            $this->isInstanceOf(CheckoutResponseTransfer::class)
        );

        $omsMock = $this->getMock(CheckoutToOmsInterface::class);
        $checkoutWorkflow = new CheckoutWorkflow([], [$mock1, $mock2], [], $omsMock);

        $checkoutWorkflow->placeOrder($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testWorkflowCallsAllPostHooks()
    {
        $mock1 = $this->getMock(CheckoutPostSaveHookInterface::class);
        $mock2 = $this->getMock(CheckoutPostSaveHookInterface::class);

        $quoteTransfer = new QuoteTransfer();

        $mock1->expects($this->once())->method('executeHook')->with(
            $this->isInstanceOf(QuoteTransfer::class),
            $this->isInstanceOf(CheckoutResponseTransfer::class)
        );

        $mock2->expects($this->once())->method('executeHook')->with(
            $this->isInstanceOf(QuoteTransfer::class),
            $this->isInstanceOf(CheckoutResponseTransfer::class)
        );

        $omsMock = $this->getMock(CheckoutToOmsInterface::class);
        $checkoutWorkflow = new CheckoutWorkflow([], [], [$mock1, $mock2], $omsMock);

        $checkoutWorkflow->placeOrder($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testWorkflowPassesResponseOn()
    {
        $checkoutResponse = $this->createBaseCheckoutResponse();
        $checkoutResponse
            ->setIsExternalRedirect(true)
            ->setRedirectUrl('anUrl');

        $mock1 = new ResponseManipulatorPreCondition($checkoutResponse);
        $mock2 = $this->getMock(CheckoutSaveOrderInterface::class);

        $quoteTransfer = new QuoteTransfer();

        $omsMock = $this->getMock(CheckoutToOmsInterface::class);
        $checkoutWorkflow = new CheckoutWorkflow([$mock1], [$mock2], [], $omsMock);

        $mock2->expects($this->once())->method('saveOrder')->with(
            $this->isInstanceOf(QuoteTransfer::class),
            $this->isInstanceOf(CheckoutResponseTransfer::class)
        );

        $checkoutWorkflow->placeOrder($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testPosthookNotCalledAndResponseReturnedOnSaveError()
    {
        $checkoutResponse = $this->createBaseCheckoutResponse();
        $error = new CheckoutErrorTransfer();

        $checkoutResponse
            ->addError($error)
            ->setIsSuccess(false);

        $mock1 = new MockOrderSaver($checkoutResponse);
        $mock2 = $this->getMock(CheckoutPostSaveHookInterface::class);

        $omsMock = $this->getMock(CheckoutToOmsInterface::class);
        $checkoutWorkflow = new CheckoutWorkflow([], [$mock1], [$mock2], $omsMock);
        $quoteTransfer = new QuoteTransfer();

        $mock2->expects($this->never())->method('executeHook');

        $result = $checkoutWorkflow->placeOrder($quoteTransfer);
        $this->assertFalse($result->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPostHookResultIsReturned()
    {
        $checkoutResponse = $this->createBaseCheckoutResponse();
        $error = new CheckoutErrorTransfer();

        $checkoutResponse
            ->setIsSuccess(true);

        $mock = new MockPostHook($checkoutResponse);

        $omsMock = $this->getMock(CheckoutToOmsInterface::class);
        $checkoutWorkflow = new CheckoutWorkflow([], [], [$mock], $omsMock);
        $quoteTransfer = new QuoteTransfer();

        $result = $checkoutWorkflow->placeOrder($quoteTransfer);

        $this->assertTrue($result->getIsSuccess());
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createBaseCheckoutResponse()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $checkoutResponseTransfer->setSaveOrder(new SaveOrderTransfer());

        return $checkoutResponseTransfer;
    }

}
