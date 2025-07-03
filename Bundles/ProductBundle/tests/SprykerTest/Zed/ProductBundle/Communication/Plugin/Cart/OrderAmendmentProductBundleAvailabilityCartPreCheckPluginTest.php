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
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductBundle\Communication\Plugin\Cart\OrderAmendmentProductBundleAvailabilityCartPreCheckPlugin;
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
 * @group OrderAmendmentProductBundleAvailabilityCartPreCheckPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductBundleAvailabilityCartPreCheckPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester
     */
    protected ProductBundleCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckReturnsSuccessfulResponseWhenBundleProductIsActive(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->createProductBundle(true);
        $itemTransfer = (new ItemTransfer())->setSku($productConcreteTransfer->getSku())->setQuantity(10);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote((new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::STORE_NAME_DE)))
            ->addItem($itemTransfer);

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductBundleAvailabilityCartPreCheckPlugin())
            ->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckReturnsSuccessfulResponseWhenBundleProductIsNotActive(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->createProductBundle(false);
        $itemTransfer = (new ItemTransfer())->setSku($productConcreteTransfer->getSku())->setQuantity(10);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote((new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::STORE_NAME_DE)))
            ->addItem($itemTransfer);

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductBundleAvailabilityCartPreCheckPlugin())
            ->check($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckReturnsSuccessfulResponseWhenBundleProductIsNotActiveAndExistsInOriginalSalesOrderItems(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->createProductBundle(false, 'sku1');
        $itemTransfer = (new ItemTransfer())->setSku($productConcreteTransfer->getSku())->setQuantity(10);
        $quoteTransfer = (new QuoteTransfer())
            ->addOriginalSalesOrderItem(
                ((new OriginalSalesOrderItemTransfer())->setSku('sku1')),
            )
            ->setStore((new StoreTransfer())->setName(static::STORE_NAME_DE));
        $cartChangeTransfer = (new CartChangeTransfer())->setQuote($quoteTransfer)->addItem($itemTransfer);

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductBundleAvailabilityCartPreCheckPlugin())
            ->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }
}
