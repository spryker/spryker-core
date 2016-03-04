<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Checkout\Business\Workflow;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Checkout\Business\Workflow\CheckoutWorkflow;
use Unit\Spryker\Zed\Checkout\Business\Fixture\MockOrderSaver;
use Unit\Spryker\Zed\Checkout\Business\Fixture\MockPostHook;
use Unit\Spryker\Zed\Checkout\Business\Fixture\ResponseManipulatorPreCondition;

/**
 * @group Spryker
 * @group Zed
 * @group Checkout
 * @group CheckoutWorkflowTest
 */
class CheckoutWorkflowTest extends Test
{

    /**
     * @return void
     */
    public function testWorkflowCallsAllPreConditions()
    {
        $mock1 = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Plugin\\CheckoutPreConditionInterface');
        $mock2 = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Plugin\\CheckoutPreConditionInterface');
        $omsMock = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Facade\\CheckoutToOmsInterface');

        $quoteTransfer = new QuoteTransfer();
        $checkoutResponse = $this->createBaseCheckoutResponse();

        $mock1->expects($this->once())->method('checkCondition')->with(
            $this->equalTo($quoteTransfer),
            $this->equalTo($checkoutResponse)
        );

        $mock2->expects($this->once())->method('checkCondition')->with(
            $this->equalTo($quoteTransfer),
            $this->equalTo($checkoutResponse)
        );

        $checkoutWorkflow = new CheckoutWorkflow([$mock1, $mock2], [], [], $omsMock);

        $checkoutWorkflow->placeOrder($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testWorkflowCallsAllOrderSavers()
    {
        $mock1 = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Plugin\\CheckoutSaveOrderInterface');
        $mock2 = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Plugin\\CheckoutSaveOrderInterface');
        $omsMock = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Facade\\CheckoutToOmsInterface');

        $quoteTransfer = new QuoteTransfer();
        $checkoutResponse = $this->createBaseCheckoutResponse();

        $mock1->expects($this->once())->method('saveOrder')->with(
            $this->equalTo($quoteTransfer),
            $this->equalTo($checkoutResponse)
        );

        $mock2->expects($this->once())->method('saveOrder')->with(
            $this->equalTo($quoteTransfer),
            $this->equalTo($checkoutResponse)
        );

        $checkoutWorkflow = new CheckoutWorkflow([], [$mock1, $mock2], [], $omsMock);

        $checkoutWorkflow->placeOrder($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testWorkflowCallsAllPostHooks()
    {
        $mock1 = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Plugin\\CheckoutPostSaveHookInterface');
        $mock2 = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Plugin\\CheckoutPostSaveHookInterface');
        $omsMock = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Facade\\CheckoutToOmsInterface');

        $quoteTransfer = new QuoteTransfer();
        $checkoutResponse = $this->createBaseCheckoutResponse();

        $mock1->expects($this->once())->method('executeHook')->with(
            $this->equalTo($quoteTransfer),
            $this->equalTo($checkoutResponse)
        );

        $mock2->expects($this->once())->method('executeHook')->with(
            $this->equalTo($quoteTransfer),
            $this->equalTo($checkoutResponse)
        );

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
        $mock2 = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Plugin\\CheckoutSaveOrderInterface');
        $omsMock = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Facade\\CheckoutToOmsInterface');

        $quoteTransfer = new QuoteTransfer();

        $checkoutWorkflow = new CheckoutWorkflow([$mock1], [$mock2], [], $omsMock);

        $mock2->expects($this->once())->method('saveOrder')->with(
            $this->equalTo($quoteTransfer),
            $this->equalTo($checkoutResponse)
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
        $mock2 = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Plugin\\CheckoutPostSaveHookInterface');
        $omsMock = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Facade\\CheckoutToOmsInterface');

        $checkoutWorkflow = new CheckoutWorkflow([], [$mock1], [$mock2], $omsMock);
        $quoteTransfer = new QuoteTransfer();

        $mock2->expects($this->never())->method('executeHook');

        $result = $checkoutWorkflow->placeOrder($quoteTransfer);
        $this->assertEquals($checkoutResponse, $result);
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
        $omsMock = $this->getMock('Spryker\\Zed\\Checkout\\Dependency\\Facade\\CheckoutToOmsInterface');

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
