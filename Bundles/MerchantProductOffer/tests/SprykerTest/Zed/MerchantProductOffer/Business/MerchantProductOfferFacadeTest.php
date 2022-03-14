<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOffer\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOffer
 * @group Business
 * @group Facade
 * @group MerchantProductOfferFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOffer\MerchantProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductOfferCollectionReturnsFilledCollection(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);

        $merchantProductOfferCriteriaTransfer = (new MerchantProductOfferCriteriaTransfer())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setSkus([$productOfferTransfer->getConcreteSku()])
            ->setIsActive(true);

        // Act
        $productOfferCollectionTransfer = $this->tester->getFacade()->getProductOfferCollection($merchantProductOfferCriteriaTransfer);

        // Assert
        $this->assertNotEmpty($productOfferCollectionTransfer->getProductOffers());
    }

    /**
     * @return void
     */
    public function testCheckingShoppingListItemWithActiveAndApprovedMerchantSucceeds(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->createShoppingListItem(true, 'approved');

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertTrue($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
        $this->assertEmpty($shoppingListPreAddItemCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckingShoppingListItemWithInactiveAndApprovedMerchantFails(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->createShoppingListItem(false, 'approved');

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $shoppingListPreAddItemCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckingShoppingListItemWithActiveAndUnapprovedMerchantFails(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->createShoppingListItem(false, 'declined');

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
        $this->assertCount(2, $shoppingListPreAddItemCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckingShoppingListItemWithInactiveAndUnapprovedMerchantFails(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->createShoppingListItem(false, 'declined');

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
        $this->assertCount(2, $shoppingListPreAddItemCheckResponseTransfer->getMessages());
    }

    /**
     * @param bool $isActiveStatus
     * @param string $merchantStatus
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function createShoppingListItem(bool $isActiveStatus, string $merchantStatus): ShoppingListItemTransfer
    {
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => $isActiveStatus,
            MerchantTransfer::STATUS => $merchantStatus,
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);

        return (new ShoppingListItemTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());
    }
}
