<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOffer\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ProductOffer\ProductOfferConfig;
use Spryker\Zed\ProductOffer\Communication\Plugin\Cart\OrderAmendmentProductOfferCartPreCheckPlugin;
use SprykerTest\Zed\ProductOffer\ProductOfferCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOffer
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group OrderAmendmentProductOfferCartPreCheckPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductOfferCartPreCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOffer\ProductOfferCommunicationTester
     */
    protected ProductOfferCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckShouldReturnSuccessWithValidProductOffers(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
        ]);
        $itemTransfer = (new ItemTransfer())
            ->setSku($productOfferTransfer->getConcreteSku())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductOfferCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckShouldReturnFailedResponseWithErrorsForInvalidProductOffers(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
        ]);
        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
        ]);
        $itemTransfer = (new ItemTransfer())
            ->setSku($productOfferTransfer->getConcreteSku())
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductOfferCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckShouldReturnFailedResponseForItemsWithoutProductOffers(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $itemTransfer = (new ItemTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductOfferCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckShouldReturnSuccessResponseForInvalidProductOffersFromOriginalOrder(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => ProductOfferConfig::STATUS_DENIED,
        ]);
        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
            ProductOfferTransfer::IS_ACTIVE => false,
        ]);
        $quoteTransfer = (new QuoteTransfer())
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setProductOfferReference($productOfferTransfer2->getProductOfferReference()),
            );
        $itemTransfer = (new ItemTransfer())
            ->setSku($productOfferTransfer->getConcreteSku())
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote($quoteTransfer);

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductOfferCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertEmpty($cartPreCheckResponseTransfer->getMessages());
    }
}
