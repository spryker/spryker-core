<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShoppingList\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShoppingList
 * @group Business
 * @group Facade
 * @group ProductOfferShoppingListFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferShoppingListFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const FAILED_SKU = 'failedsku';

    /**
     * @var string
     */
    protected const FAILED_PRODUCT_OFFER_REFERENCE = 'failedoffer';

    /**
     * @var \SprykerTest\Zed\ProductOfferShoppingList\ProductOfferShoppingListBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCheckProductOfferShoppingListItemReturnsResponseTransferWithError(): void
    {
        // Arrange
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setProductOfferReference(static::FAILED_PRODUCT_OFFER_REFERENCE)
            ->setSku(static::FAILED_SKU);

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkProductOfferShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $shoppingListPreAddItemCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckProductOfferShoppingListItemReturnsResponseTransferWithIsActiveAndNotApprovedErrors(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => false,
            ProductOfferTransfer::APPROVAL_STATUS => 'denied',
        ]);
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setSku($productOfferTransfer->getConcreteSku());

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkProductOfferShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
        $this->assertCount(2, $shoppingListPreAddItemCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckProductOfferShoppingListItemReturnsResponseTransferWithoutError(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => 'approved',
        ]);

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setSku($productOfferTransfer->getConcreteSku());

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkProductOfferShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertTrue($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $shoppingListPreAddItemCheckResponseTransfer->getMessages());
    }
}
