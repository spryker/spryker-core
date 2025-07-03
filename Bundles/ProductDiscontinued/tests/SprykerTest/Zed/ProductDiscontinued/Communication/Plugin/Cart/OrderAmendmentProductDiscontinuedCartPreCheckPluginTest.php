<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinued\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductDiscontinued\Communication\Plugin\Cart\OrderAmendmentProductDiscontinuedCartPreCheckPlugin;
use SprykerTest\Zed\ProductDiscontinued\ProductDiscontinuedCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductDiscontinued
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group OrderAmendmentProductDiscontinuedCartPreCheckPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductDiscontinuedCartPreCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductDiscontinued\ProductDiscontinuedCommunicationTester
     */
    protected ProductDiscontinuedCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckShouldReturnSuccessResponseForNotDiscontinuedProducts(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $itemTransfer = (new ItemTransfer())
            ->setSku($productTransfer->getSku());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductDiscontinuedCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckShouldReturnFailedResponseWithErrorsForDiscontinuedProducts(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($productTransfer->getIdProductConcrete());
        $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer);

        $itemTransfer = (new ItemTransfer())
            ->setSku($productTransfer->getSku());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductDiscontinuedCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckShouldReturnSuccessResponseForDiscontinuedProductsFromOriginalOrder(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($productTransfer->getIdProductConcrete());
        $this->tester->getFacade()->markProductAsDiscontinued($productDiscontinueRequestTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setSku($productTransfer->getSku()),
            );
        $itemTransfer = (new ItemTransfer())
            ->setSku($productTransfer->getSku());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote($quoteTransfer);

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductDiscontinuedCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertEmpty($cartPreCheckResponseTransfer->getMessages());
    }
}
