<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Checkout\Model\Process\Steps;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Client\Checkout\CheckoutClientInterface;
use Spryker\Yves\Checkout\Process\Steps\AbstractPlaceOrderStep;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Checkout
 * @group Model
 * @group Process
 * @group Steps
 * @group AbstractPlaceOrderStepTest
 * Add your own group annotations below this line
 */
class AbstractPlaceOrderStepTest extends Unit
{
    public const ORDER_REFERENCE = 'order reference';
    public const ESCAPE_ROUTE = 'escapeRoute';
    public const STEP_ROUTE = 'stepRoute';
    public const ERROR_CODE_123 = 123;
    public const ESCAPE_ROUTE_123 = 'escapeRoute123';
    public const EXTERNAL_REDIRECT_URL = 'externalRedirectUrl';

    /**
     * @return void
     */
    public function testRequireInputReturnFalse()
    {
        $checkoutClientMock = $this->getCheckoutClientMock();
        $abstractPlaceOrderStepMock = $this->getAbstractPlaceOrderStep($checkoutClientMock);

        $this->assertFalse($abstractPlaceOrderStepMock->requireInput(new QuoteTransfer()));
    }

    /**
     * @return void
     */
    public function testExecuteShouldSetExternalRedirectUrlIfResponseContainsOne()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsExternalRedirect(true);
        $checkoutResponseTransfer->setRedirectUrl(self::EXTERNAL_REDIRECT_URL);

        $checkoutClientMock = $this->getCheckoutClientMock();
        $checkoutClientMock->method('placeOrder')->willReturn($checkoutResponseTransfer);
        $abstractPlaceOrderStepMock = $this->getAbstractPlaceOrderStep($checkoutClientMock);

        $abstractPlaceOrderStepMock->execute($this->getRequest(), new QuoteTransfer());
        $this->assertSame(self::EXTERNAL_REDIRECT_URL, $abstractPlaceOrderStepMock->getExternalRedirectUrl());
    }

    /**
     * @return void
     */
    public function testExecuteShouldSetOrderReferenceIfResponseContainsOne()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $saveOrderTransfer = new SaveOrderTransfer();
        $saveOrderTransfer->setOrderReference(self::ORDER_REFERENCE);
        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);

        $checkoutClientMock = $this->getCheckoutClientMock();
        $checkoutClientMock->method('placeOrder')->willReturn($checkoutResponseTransfer);
        $abstractPlaceOrderStepMock = $this->getAbstractPlaceOrderStep($checkoutClientMock);

        $quoteTransfer = new QuoteTransfer();
        $abstractPlaceOrderStepMock->execute($this->getRequest(), $quoteTransfer);
        $this->assertSame(self::ORDER_REFERENCE, $quoteTransfer->getOrderReference());
    }

    /**
     * @return void
     */
    public function testPostConditionReturnTrueWhenOrderReferenceGivenAndResponseIsSuccessful()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true);

        $checkoutClientMock = $this->getCheckoutClientMock();
        $checkoutClientMock->method('placeOrder')->willReturn($checkoutResponseTransfer);

        $abstractPlaceOrderStepMock = $this->getAbstractPlaceOrderStep($checkoutClientMock);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setOrderReference(self::ORDER_REFERENCE);
        $abstractPlaceOrderStepMock->execute($this->getRequest(), $quoteTransfer);

        $this->assertTrue($abstractPlaceOrderStepMock->postCondition($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testPostConditionReturnFalseWhenNoOrderReferenceGiven()
    {
        $abstractPlaceOrderStepMock = $this->getAbstractPlaceOrderStep(
            $this->getCheckoutClientMock()
        );

        $this->assertFalse($abstractPlaceOrderStepMock->postCondition(new QuoteTransfer()));
    }

    /**
     * @return void
     */
    public function testPostConditionReturnFalseWhenOrderReferenceGivenAndResponseIsNotSuccessful()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(false);

        $checkoutClientMock = $this->getCheckoutClientMock();
        $checkoutClientMock->method('placeOrder')->willReturn($checkoutResponseTransfer);

        $abstractPlaceOrderStepMock = $this->getAbstractPlaceOrderStep($checkoutClientMock);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setOrderReference(self::ORDER_REFERENCE);
        $abstractPlaceOrderStepMock->execute($this->getRequest(), $quoteTransfer);

        $this->assertFalse($abstractPlaceOrderStepMock->postCondition($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testPostConditionDoesNotChangeEscapeRouteIfResponseFalseAndNoErrorCodeMatches()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(false);

        $checkoutClientMock = $this->getCheckoutClientMock();
        $checkoutClientMock->method('placeOrder')->willReturn($checkoutResponseTransfer);

        $abstractPlaceOrderStepMock = $this->getAbstractPlaceOrderStep($checkoutClientMock);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setOrderReference(self::ORDER_REFERENCE);
        $abstractPlaceOrderStepMock->execute($this->getRequest(), $quoteTransfer);

        $this->assertFalse($abstractPlaceOrderStepMock->postCondition($quoteTransfer));

        $this->assertSame(self::ESCAPE_ROUTE, $abstractPlaceOrderStepMock->getEscapeRoute());
    }

    /**
     * @return void
     */
    public function testPostConditionChangeErrorRouteIfResponseFalseAndErrorCodeMatches()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(false);
        $checkoutErrorTransfer = new CheckoutErrorTransfer();
        $checkoutErrorTransfer->setErrorCode(static::ERROR_CODE_123);
        $checkoutResponseTransfer->addError($checkoutErrorTransfer);

        $checkoutClientMock = $this->getCheckoutClientMock();
        $checkoutClientMock->method('placeOrder')->willReturn($checkoutResponseTransfer);

        $abstractPlaceOrderStepMock = $this->getAbstractPlaceOrderStep($checkoutClientMock);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setOrderReference(self::ORDER_REFERENCE);
        $abstractPlaceOrderStepMock->execute($this->getRequest(), $quoteTransfer);

        $this->assertFalse($abstractPlaceOrderStepMock->postCondition($quoteTransfer));

        $this->assertSame(self::ESCAPE_ROUTE_123, $abstractPlaceOrderStepMock->getPostConditionErrorRoute());
    }

    /**
     * @param \Spryker\Client\Checkout\CheckoutClientInterface $checkoutClient
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Checkout\Process\Steps\AbstractPlaceOrderStep
     */
    protected function getAbstractPlaceOrderStep(CheckoutClientInterface $checkoutClient)
    {
        $errorCodeToEscapeRouteMatching = [
            self::ERROR_CODE_123 => self::ESCAPE_ROUTE_123,
        ];
        $abstractPlaceOrderStepMock = $this->getMockForAbstractClass(AbstractPlaceOrderStep::class, [$checkoutClient, self::STEP_ROUTE, self::ESCAPE_ROUTE, $errorCodeToEscapeRouteMatching]);

        return $abstractPlaceOrderStepMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Checkout\CheckoutClientInterface
     */
    private function getCheckoutClientMock()
    {
        return $this->getMockBuilder(CheckoutClientInterface::class)->getMock();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return Request::create('foo');
    }
}
