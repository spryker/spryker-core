<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Wishlist\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\WishlistItemCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Wishlist
 * @group Business
 * @group Facade
 * @group GetWishlistItemFacadeTest
 * Add your own group annotations below this line
 */
class GetWishlistItemFacadeTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_WISHLIST_ITEM = 77777;

    /**
     * @var string
     */
    protected const NEW_WISHLIST_MANE = 'new_wishlist_name';

    /**
     * @var \SprykerTest\Zed\Wishlist\WishlistBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\WishlistTransfer
     */
    protected $wishlistTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->customerTransfer = $this->tester->haveCustomer();
        $this->productConcreteTransfer = $this->tester->haveProduct();

        $this->wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            WishlistTransfer::NAME => static::NEW_WISHLIST_MANE,
        ]);
    }

    /**
     * @return void
     */
    public function testGetWishlistItemFromPersistenceByIdWishlistItem(): void
    {
        // Arrange
        $wishlistItemTransfer = $this->createDefaultWishlistItem();

        $wishlistItemCriteriaTransfer = (new WishlistItemCriteriaTransfer())
            ->setIdWishlistItem($wishlistItemTransfer->getIdWishlistItem());

        // Act
        $wishlistItemResponseTransfer = $this->tester
            ->getFacade()
            ->getWishlistItem($wishlistItemCriteriaTransfer);

        // Assert
        $this->assertTrue($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            $wishlistItemTransfer->getIdWishlistItem(),
            $wishlistItemResponseTransfer->getWishlistItem()->getIdWishlistItem(),
        );
    }

    /**
     * @return void
     */
    public function testGetWishlistItemWithFakeIdWishlistItem(): void
    {
        // Arrange
        $wishlistItemCriteriaTransfer = (new WishlistItemCriteriaTransfer())
            ->setIdWishlistItem(static::FAKE_ID_WISHLIST_ITEM);

        // Act
        $wishlistItemResponseTransfer = $this->tester
            ->getFacade()
            ->getWishlistItem($wishlistItemCriteriaTransfer);

        // Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertNull($wishlistItemResponseTransfer->getWishlistItem());
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected function createDefaultWishlistItem(): WishlistItemTransfer
    {
        return $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlistTransfer->getName(),
            WishlistItemTransfer::SKU => $this->productConcreteTransfer->getSku(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
        ]);
    }
}
