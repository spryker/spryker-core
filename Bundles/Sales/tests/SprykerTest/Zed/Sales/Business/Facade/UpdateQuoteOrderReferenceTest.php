<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Dependency\Client\SalesToQuoteClientBridge;
use Spryker\Zed\Sales\Dependency\Client\SalesToQuoteClientInterface;
use Spryker\Zed\Sales\SalesDependencyProvider;

class UpdateQuoteOrderReferenceTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    const TEST_ORDER_REFERENCE = 'TEST';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(SalesDependencyProvider::CLIENT_QUOTE,
            new SalesToQuoteClientBridge($this->createMock(QuoteClientInterface::class))
        );
    }

    public function testUpdateQuoteOrderReferenceWithResponseIsSuccess()
    {
        // Arange
        $quoteTransfer = new QuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true)
            ->setSaveOrder(
                (new SaveOrderTransfer())->setOrderReference(self::TEST_ORDER_REFERENCE)
            );

        // Act
        $this->tester
            ->getFacade()
            ->updateQuoteOrderReference($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertEquals(self::TEST_ORDER_REFERENCE, $quoteTransfer->getOrderReference());
    }

    public function testUpdateQuoteOrderReferenceWithResponseIsFailure()
    {
        // Arange
        $quoteTransfer = new QuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(false)
            ->setSaveOrder(
                (new SaveOrderTransfer())->setOrderReference(self::TEST_ORDER_REFERENCE)
            );

        // Act
        $this->tester
            ->getFacade()
            ->updateQuoteOrderReference($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getOrderReference());
    }

    public function testUpdateQuoteOrderReferenceWithResponseIsSuccessButNoSaveOrder()
    {
        // Arange
        $quoteTransfer = new QuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true);

        // Act
        $this->tester
            ->getFacade()
            ->updateQuoteOrderReference($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getOrderReference());
    }
}
