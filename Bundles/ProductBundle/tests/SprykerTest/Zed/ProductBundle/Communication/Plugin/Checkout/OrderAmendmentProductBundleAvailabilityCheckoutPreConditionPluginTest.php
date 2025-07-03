<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductBundle\Communication\Plugin\Checkout\OrderAmendmentProductBundleAvailabilityCheckoutPreConditionPlugin;
use SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group OrderAmendmentProductBundleAvailabilityCheckoutPreConditionPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductBundleAvailabilityCheckoutPreConditionPluginTest extends Unit
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
    public function testCheckConditionReturnsSuccessfulResponseWhenBundleProductIsActive(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->createProductBundle(true);
        $itemTransfer = (new ItemTransfer())->setSku($productConcreteTransfer->getSku())->setQuantity(10);
        $quoteTransfer = (new QuoteTransfer())
            ->setStore((new StoreTransfer())->setName(static::STORE_NAME_DE))
            ->addItem($itemTransfer);

        // Act
        $isValid = (new OrderAmendmentProductBundleAvailabilityCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsSuccessfulResponseWhenBundleProductIsNotActive(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->createProductBundle(false);
        $itemTransfer = (new ItemTransfer())->setSku('sku1')->setQuantity(10);
        $quoteTransfer = (new QuoteTransfer())
            ->setStore((new StoreTransfer())->setName(static::STORE_NAME_DE))
            ->addBundleItem((new ItemTransfer())->setSku($productConcreteTransfer->getSkuOrFail())->setQuantity(10))
            ->addItem($itemTransfer);

        // Act
        $isValid = (new OrderAmendmentProductBundleAvailabilityCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsSuccessfulResponseWhenBundleProductIsNotActiveAndExistsInOriginalSalesOrderItems(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->createProductBundle(false, 'sku1');
        $itemTransfer = (new ItemTransfer())->setSku('sku1')->setQuantity(10);
        $quoteTransfer = (new QuoteTransfer())
            ->setStore((new StoreTransfer())->setName(static::STORE_NAME_DE))
            ->addBundleItem((new ItemTransfer())->setSku($productConcreteTransfer->getSkuOrFail())->setQuantity(10))
            ->addItem($itemTransfer)
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setSku('sku1'),
            );

        // Act
        $isValid = (new OrderAmendmentProductBundleAvailabilityCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }
}
