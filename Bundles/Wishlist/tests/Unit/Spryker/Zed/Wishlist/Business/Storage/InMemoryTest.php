<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Wishlist\Business\Storage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Wishlist\Business\Storage\InMemory;
use Spryker\Zed\Wishlist\Dependency\Facade\WishlistToProductBridge;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Wishlist
 * @group Business
 * @group Storage
 * @group InMemoryTest
 */
class InMemoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAddItemToExisting()
    {
        $wishlistTransfer = new WishlistTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistTransfer->addItem($wishlistItem);

        $productFacadeMock = $this->createProductFacadeProductConcreteMock();

        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->addItems($wishlistChangeTransfer);

        $wishlistItem = $wishlist->getItems()[0];

        $this->assertEquals(2, $wishlistItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testAddNewItem()
    {
        $productFacadeMock = $this->createProductFacadeProductConcreteMock();
        $wishlistTransfer = new WishlistTransfer();
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->addItems($wishlistChangeTransfer);

        $wishlistItem = $wishlist->getItems()[0];

        $this->assertEquals(1, $wishlistItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testReduceExistingItem()
    {
        $productFacadeMock = $this->createProductFacadeProductConcreteMock();
        $wishlistTransfer = new WishlistTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(10);
        $wishlistTransfer->addItem($wishlistItem);
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->decreaseItems($wishlistChangeTransfer);

        $wishlistItem = $wishlist->getItems()[0];

        $this->assertEquals(9, $wishlistItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testReduceIfLastExisting()
    {
        $productFacadeMock = $this->createProductFacadeProductConcreteMock();
        $wishlistTransfer = new WishlistTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistTransfer->addItem($wishlistItem);
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->decreaseItems($wishlistChangeTransfer);

        $this->assertCount(0, $wishlist->getItems());
    }

    /**
     * @return void
     */
    public function testRemoveItem()
    {
        $productFacadeMock = $this->createProductFacadeProductConcreteMock();
        $wishlistTransfer = new WishlistTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(10);
        $wishlistTransfer->addItem($wishlistItem);
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(0);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->decreaseItems($wishlistChangeTransfer);

        $this->assertCount(0, $wishlist->getItems());
    }

    /**
     * @return void
     */
    public function testIncreaseItem()
    {
        $productFacadeMock = $this->createProductFacadeProductConcreteMock();
        $wishlistTransfer = new WishlistTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistTransfer->addItem($wishlistItem);
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->increaseItems($wishlistChangeTransfer);

        $wishlistItem = $wishlist->getItems()[0];

        $this->assertEquals(2, $wishlistItem->getQuantity());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Wishlist\Dependency\Facade\WishlistToProductInterface
     */
    public function createProductFacadeProductConcreteMock()
    {
        $concreateProductTransfer = new ProductConcreteTransfer();
        $concreateProductTransfer->setIdProductConcrete(1);

        $productFacadeMock = $this
            ->getMockBuilder(WishlistToProductBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productFacadeMock->expects($this->any())->method('getProductConcrete')
            ->will($this->returnValue($concreateProductTransfer));

        return $productFacadeMock;
    }

}
