<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOffer\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOffer\Communication\Plugin\Checkout\OrderAmendmentProductOfferCheckoutPreConditionPlugin;
use SprykerTest\Zed\ProductOffer\ProductOfferCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOffer
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group OrderAmendmentProductOfferCheckoutPreConditionPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductOfferCheckoutPreConditionPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOffer\ProductOfferCommunicationTester
     */
    protected ProductOfferCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnTrueForValidProductOffer(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => 'approved',
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
        ]);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        //Act
        $isValid = (new OrderAmendmentProductOfferCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        //Assert
        $this->assertTrue(
            $isValid,
            'Expects that quote transfer will be valid when product offer is valid.',
        );
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnFalseForInvalidProductOffer(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => false,
            ProductOfferTransfer::APPROVAL_STATUS => 'approved',
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
        ]);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        //Act
        $isValid = (new OrderAmendmentProductOfferCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        //Assert
        $this->assertFalse(
            $isValid,
            'Expects that quote transfer will be invalid when product offer is inactive.',
        );
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnFalseForNotApprovedProductOffer(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => 'waiting_for_approval',
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
        ]);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        //Act
        $isValid = (new OrderAmendmentProductOfferCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        //Assert
        $this->assertFalse(
            $isValid,
            'Expects that quote transfer will be invalid when product offer not approved.',
        );
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnTrueForInactiveProductOfferFromOriginalOrder(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => false,
            ProductOfferTransfer::APPROVAL_STATUS => 'approved',
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
        ]);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer)
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setProductOfferReference($productOfferTransfer->getProductOfferReference()),
            );

        //Act
        $isValid = (new OrderAmendmentProductOfferCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        //Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnTrueForNotApprovedProductOffersFromOriginalOrder(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => 'waiting_for_approval',
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcrete(),
        ]);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer)
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setProductOfferReference($productOfferTransfer->getProductOfferReference()),
            );

        //Act
        $isValid = (new OrderAmendmentProductOfferCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, new CheckoutResponseTransfer());

        //Assert
        $this->assertTrue($isValid);
    }
}
