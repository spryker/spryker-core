<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinued\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductDiscontinued\Communication\Plugin\Checkout\OrderAmendmentProductDiscontinuedCheckoutPreConditionPlugin;
use SprykerTest\Zed\ProductDiscontinued\ProductDiscontinuedCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductDiscontinued
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group OrderAmendmentProductDiscontinuedCheckoutPreConditionPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductDiscontinuedCheckoutPreConditionPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductDiscontinued\ProductDiscontinuedCommunicationTester
     */
    protected ProductDiscontinuedCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnTrueForNotDiscontinuedProducts(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $itemTransfer = (new ItemTransfer())
            ->setSku($productTransfer->getSku());
        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer);

        // Act
        $isValid = (new OrderAmendmentProductDiscontinuedCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnFalseForDiscontinuedProducts(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($productTransfer->getIdProductConcrete());
        $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer);

        $itemTransfer = (new ItemTransfer())
            ->setSku($productTransfer->getSku());
        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = (new OrderAmendmentProductDiscontinuedCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnTrueForDiscontinuedProductsFromOriginalOrder(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($productTransfer->getIdProductConcrete());
        $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer);
        $itemTransfer = (new ItemTransfer())
            ->setSku($productTransfer->getSku());

        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer)
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setSku($productTransfer->getSku()),
            );

        // Act
        $isValid = (new OrderAmendmentProductDiscontinuedCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }
}
