<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductCartConnector\Communication\Plugin\Cart\OrderAmendmentProductExistsCartPreCheckPlugin;
use SprykerTest\Zed\ProductCartConnector\ProductCartConnectorCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCartConnector
 * @group Communication
 * @group Plugin
 * @group OrderAmendmentProductExistsCartPreCheckPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductExistsCartPreCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductCartConnector\ProductCartConnectorCommunicationTester
     */
    protected ProductCartConnectorCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckShouldReturnSuccessResponseForActiveProduct(): void
    {
        // Arrange
        $activeProductConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        $itemTransfer = (new ItemTransfer())
            ->setSku($activeProductConcreteTransfer->getSku())
            ->setQuantity(2);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote((new QuoteTransfer()))
            ->addItem($itemTransfer);

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductExistsCartPreCheckPlugin())
            ->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckShouldReturnFailedResponseForInactiveProduct(): void
    {
        // Arrange
        $activeProductConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);

        $itemTransfer = (new ItemTransfer())
            ->setSku($activeProductConcreteTransfer->getSku())
            ->setQuantity(2);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote((new QuoteTransfer()))
            ->addItem($itemTransfer);

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductExistsCartPreCheckPlugin())
            ->check($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckShouldReturnSuccessResponseForInactiveProductAndItemsFromOriginalOrder(): void
    {
        // Arrange
        $activeProductConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setSku($activeProductConcreteTransfer->getSku()),
            );
        $itemTransfer = (new ItemTransfer())
            ->setSku($activeProductConcreteTransfer->getSku())
            ->setQuantity(2);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote($quoteTransfer);

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductExistsCartPreCheckPlugin())
            ->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }
}
