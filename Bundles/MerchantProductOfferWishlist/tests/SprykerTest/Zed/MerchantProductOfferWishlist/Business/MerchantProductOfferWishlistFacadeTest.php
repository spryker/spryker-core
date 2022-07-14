<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferWishlist\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferWishlist
 * @group Business
 * @group Facade
 * @group MerchantProductOfferWishlistFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferWishlistFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferWishlist\MerchantProductOfferWishlistBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCheckWishlistItemProductOfferRelationSucceeds(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->checkWishlistItemProductOfferRelation($wishlistItemTransfer);

        // Assert
        $this->assertTrue($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckWishlistItemProductOfferRelationDoesNotSucceed(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem(false);

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->checkWishlistItemProductOfferRelation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckUpdateWishlistItemProductOfferRelationSucceeds(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->checkUpdateWishlistItemProductOfferRelation($wishlistItemTransfer);

        // Assert
        $this->assertTrue($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckUpdateWishlistItemProductOfferRelationDoesNotSucceed(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem(false);

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->checkUpdateWishlistItemProductOfferRelation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeCreationSucceeds(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeCreation($wishlistItemTransfer);

        // Assert
        $this->assertTrue($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeCreationDoesNotSucceedWithWrongOfferRelation(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem(false);

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeCreation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeCreationDoesNotSucceedWhenOfferIsNotActive(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer(false);
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeCreation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeCreationDoesNotSucceedWhenOfferIsNotApproved(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer(true, false);
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeCreation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeCreationDoesNotSucceedWhenMerchantIsNotActive(): void
    {
        // Arrange
        $this->tester->setUpMerchant(false);
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeCreation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeCreationDoesNotSucceedWhenMerchantIsNotApproved(): void
    {
        // Arrange
        $this->tester->setUpMerchant(true, false);
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeCreation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeUpdateSucceeds(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeUpdate($wishlistItemTransfer);

        // Assert
        $this->assertTrue($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeUpdateDoesNotSucceedWithWrongOfferRelation(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem(false);

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeUpdate($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeUpdateDoesNotSucceedWhenOfferIsNotActive(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer(false);
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeUpdate($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeUpdateDoesNotSucceedWhenOfferIsNotApproved(): void
    {
        // Arrange
        $this->tester->setUpMerchant();
        $this->tester->setUpMerchantProductOffer(true, false);
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeUpdate($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeUpdateDoesNotSucceedWhenMerchantIsNotActive(): void
    {
        // Arrange
        $this->tester->setUpMerchant(false);
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeUpdate($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemProductOfferBeforeUpdateDoesNotSucceedWhenMerchantIsNotApproved(): void
    {
        // Arrange
        $this->tester->setUpMerchant(true, false);
        $this->tester->setUpMerchantProductOffer();
        $wishlistItemTransfer = $this->tester->createProductOfferWishlistItem();

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateWishlistItemProductOfferBeforeUpdate($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }
}
