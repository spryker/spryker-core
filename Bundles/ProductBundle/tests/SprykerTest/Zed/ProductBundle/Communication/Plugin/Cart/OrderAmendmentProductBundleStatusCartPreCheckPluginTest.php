<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\ProductBundle\Communication\Plugin\Cart\OrderAmendmentProductBundleStatusCartPreCheckPlugin;
use SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group OrderAmendmentProductBundleStatusCartPreCheckPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductBundleStatusCartPreCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester
     */
    protected ProductBundleCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckThrowsExceptionWhenQuoteIsNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\CartChangeTransfer` is null.');

        // Act
        (new OrderAmendmentProductBundleStatusCartPreCheckPlugin())->check(new CartChangeTransfer());
    }

    /**
     * @return void
     */
    public function testCheckReturnsSuccessfulResponseWhenBundleProductIsActive(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProductBundle(
            $this->tester->haveFullProduct(),
        );
        $cartChangeTransfer = (new CartChangeTransfer())->addItem(
            (new ItemTransfer())->setSku($productConcreteTransfer->getSku()),
        )->setQuote(new QuoteTransfer());

        // Act
        $cartChangeTransfer = (new OrderAmendmentProductBundleStatusCartPreCheckPlugin())->check($cartChangeTransfer);

        $this->assertTrue($cartChangeTransfer->getIsSuccess());
        $this->assertEmpty($cartChangeTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckReturnsFailedResponseWhenBundleProductIsNotActive(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProductBundle(
            $this->tester->haveFullProduct(),
            [],
            [
                'isActive' => false,
            ],
        );
        $cartChangeTransfer = (new CartChangeTransfer())->addItem(
            (new ItemTransfer())->setSku($productConcreteTransfer->getSku()),
        )->setQuote(new QuoteTransfer());

        // Act
        $cartChangeTransfer = (new OrderAmendmentProductBundleStatusCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartChangeTransfer->getIsSuccess());
        $this->assertNotEmpty($cartChangeTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckReturnsSuccessfulResponseWhenBundleProductIsNotActiveAndExistsInOriginalSalesOrderItems(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProductBundle(
            $this->tester->haveFullProduct(),
            [],
            [
                'isActive' => false,
            ],
        );
        $quoteTransfer = (new QuoteTransfer())->addOriginalSalesOrderItem(
            (new OriginalSalesOrderItemTransfer())->setSku($productConcreteTransfer->getSku()),
        );
        $cartChangeTransfer = (new CartChangeTransfer())->addItem(
            (new ItemTransfer())->setSku($productConcreteTransfer->getSku()),
        )->setQuote($quoteTransfer);

        // Act
        $cartChangeTransfer = (new OrderAmendmentProductBundleStatusCartPreCheckPlugin())->check($cartChangeTransfer);

        $this->assertTrue($cartChangeTransfer->getIsSuccess());
        $this->assertEmpty($cartChangeTransfer->getMessages());
    }
}
