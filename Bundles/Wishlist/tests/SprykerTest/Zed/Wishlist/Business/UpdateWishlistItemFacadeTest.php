<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Wishlist\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Wishlist\WishlistDependencyProvider;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\UpdateItemPreCheckPluginInterface;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistPreUpdateItemPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Wishlist
 * @group Business
 * @group Facade
 * @group UpdateWishlistItemFacadeTest
 * Add your own group annotations below this line
 */
class UpdateWishlistItemFacadeTest extends Test
{
    /**
     * @var int
     */
    protected const FAKE_ID_WISHLIST_ITEM = 88888;

    /**
     * @var string
     */
    protected const FAKE_SKU = 'FAKE_SKU';

    /**
     * @see \Spryker\Zed\Wishlist\Business\Updater\WishlistItemUpdater::GLOSSARY_KEY_WISHLIST_ITEM_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_WISHLIST_ITEM_NOT_FOUND = 'wishlist.validation.error.wishlist_item_not_found';

    /**
     * @see \Spryker\Zed\Wishlist\Business\Updater\WishlistItemUpdater::GLOSSARY_KEY_WISHLIST_ITEM_CANNOT_BE_UPDATED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_WISHLIST_ITEM_CANNOT_BE_UPDATED = 'wishlist.validation.error.wishlist_item_cannot_be_updated';

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
    public function testUpdateWishlistItemEnsureThatWishlistItemPropertyCanBeUpdated(): void
    {
        // Arrange
        $wishlistItemTransfer = $this->createDefaultWishlistItem();
        $newProductConcrete = $this->tester->haveProduct();

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setIdWishlistItem($wishlistItemTransfer->getIdWishlistItem())
            ->setSku($newProductConcrete->getSku());

        // Act
        $wishlistItemResponseTransfer = $this->tester
            ->getFacade()
            ->updateWishlistItem($wishlistItemTransfer);

        // Assert
        $this->assertTrue($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            $newProductConcrete->getSku(),
            $this->tester->getWishlistItemFromPersistence($wishlistItemTransfer->getIdWishlistItem())->getSku(),
            'Wishlist item property was not updated in database storage.',
        );
    }

    /**
     * @return void
     */
    public function testUpdateWishlistItemWithFakeWishlistItemId(): void
    {
        // Arrange
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setSku($this->productConcreteTransfer->getSku())
            ->setIdWishlistItem(static::FAKE_ID_WISHLIST_ITEM);

        // Act
        $wishlistItemResponseTransfer = $this->tester
            ->getFacade()
            ->updateWishlistItem($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::GLOSSARY_KEY_WISHLIST_ITEM_NOT_FOUND,
            $wishlistItemResponseTransfer->getMessages()->offsetGet(0)->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateWishlistItemWithoutExpectedProperties(): void
    {
        // Arrange
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setIdWishlistItem(null);

        // Act
        $wishlistItemResponseTransfer = $this->tester
            ->getFacade()
            ->updateWishlistItem($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::GLOSSARY_KEY_WISHLIST_ITEM_CANNOT_BE_UPDATED,
            $wishlistItemResponseTransfer->getMessages()->offsetGet(0)->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateWishlistItemEnsureThatNewProductIsExists(): void
    {
        // Arrange
        $wishlistItemTransfer = $this->createDefaultWishlistItem();

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setIdWishlistItem($wishlistItemTransfer->getIdWishlistItem())
            ->setSku(static::FAKE_SKU);

        // Act
        $wishlistItemResponseTransfer = $this->tester
            ->getFacade()
            ->updateWishlistItem($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::GLOSSARY_KEY_WISHLIST_ITEM_CANNOT_BE_UPDATED,
            $wishlistItemResponseTransfer->getMessages()->offsetGet(0)->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateWishlistItemEnsureThatNewProductIsActive(): void
    {
        // Arrange
        $wishlistItemTransfer = $this->createDefaultWishlistItem();
        $newProductConcrete = $this->tester->haveProduct([ProductConcreteTransfer::IS_ACTIVE => false]);

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setIdWishlistItem($wishlistItemTransfer->getIdWishlistItem())
            ->setSku($newProductConcrete->getSku());

        // Act
        $wishlistItemResponseTransfer = $this->tester
            ->getFacade()
            ->updateWishlistItem($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::GLOSSARY_KEY_WISHLIST_ITEM_CANNOT_BE_UPDATED,
            $wishlistItemResponseTransfer->getMessages()->offsetGet(0)->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateWishlistItemEnsureThatUpdateItemPreCheckPluginStackExecuted(): void
    {
        // Arrange
        $wishlistItemTransfer = $this->createDefaultWishlistItem();
        $newProductConcrete = $this->tester->haveProduct();

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setIdWishlistItem($wishlistItemTransfer->getIdWishlistItem())
            ->setSku($newProductConcrete->getSku());

        // Assert
        $this->tester->setDependency(WishlistDependencyProvider::PLUGINS_UPDATE_ITEM_PRE_CHECK, [
            $this->getUpdateItemPreCheckPluginMock(),
        ]);

        // Act
        $this->tester->getFacade()->updateWishlistItem($wishlistItemTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateWishlistItemEnsureThatWishlistPreUpdateItemPluginStackExecuted(): void
    {
        // Arrange
        $wishlistItemTransfer = $this->createDefaultWishlistItem();
        $newProductConcrete = $this->tester->haveProduct();

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setIdWishlistItem($wishlistItemTransfer->getIdWishlistItem())
            ->setSku($newProductConcrete->getSku());

        // Assert
        $this->tester->setDependency(WishlistDependencyProvider::PLUGINS_WISHLIST_PRE_UPDATE_ITEM, [
            $this->getWishlistPreUpdateItemPluginMock(),
        ]);

        // Act
        $this->tester->getFacade()->updateWishlistItem($wishlistItemTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\WishlistExtension\Dependency\Plugin\UpdateItemPreCheckPluginInterface
     */
    protected function getUpdateItemPreCheckPluginMock(): UpdateItemPreCheckPluginInterface
    {
        $updateItemPreCheckPluginMock = $this
            ->getMockBuilder(UpdateItemPreCheckPluginInterface::class)
            ->getMock();

        $updateItemPreCheckPluginMock
            ->expects($this->once())
            ->method('check')
            ->willReturn((new WishlistPreUpdateItemCheckResponseTransfer())->setIsSuccess(true));

        return $updateItemPreCheckPluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistPreUpdateItemPluginInterface
     */
    protected function getWishlistPreUpdateItemPluginMock(): WishlistPreUpdateItemPluginInterface
    {
        $wishlistPreUpdateItemPluginMock = $this
            ->getMockBuilder(WishlistPreUpdateItemPluginInterface::class)
            ->getMock();

        $wishlistPreUpdateItemPluginMock
            ->expects($this->once())
            ->method('preUpdateItem')
            ->willReturnCallback(function (WishlistItemTransfer $wishlistItemTransfer) {
                return $wishlistItemTransfer;
            });

        return $wishlistPreUpdateItemPluginMock;
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
