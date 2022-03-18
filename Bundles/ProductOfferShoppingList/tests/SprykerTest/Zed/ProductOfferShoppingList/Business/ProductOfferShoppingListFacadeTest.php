<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShoppingList\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_STATUS_APPROVED = 'approved';

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
        $storeName = $this->tester->getLocator()->store()->facade()->getCurrentStore()->getName();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => $storeName]);

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => false,
            ProductOfferTransfer::APPROVAL_STATUS => 'denied',
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
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
        $storeName = $this->tester->getLocator()->store()->facade()->getCurrentStore()->getName();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => $storeName]);

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_STATUS_APPROVED,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
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

    /**
     * @return void
     */
    public function testShoppingListItemWithInvalidStoreFails(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'AU']);

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_STATUS_APPROVED,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setSku($productOfferTransfer->getConcreteSku());

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkProductOfferShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $shoppingListPreAddItemCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testShoppingListItemWithValidStoreSucceeds(): void
    {
        // Arrange
        $storeName = $this->tester->getLocator()->store()->facade()->getCurrentStore()->getName();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => $storeName]);

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_STATUS_APPROVED,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
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
