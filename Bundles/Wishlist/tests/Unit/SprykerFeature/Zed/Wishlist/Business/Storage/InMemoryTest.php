<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Bundles\Wishlist\tests\Unit\SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerFeature\Zed\Wishlist\Business\Storage\InMemory;

class InMemoryTest extends \PHPUnit_Framework_TestCase
{

    public function testAddItemToExisting()
    {
        $wishlistTransfer = new WishlistTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistTransfer->addItem($wishlistItem);

        $productFacadeMock = $this->createProductFacadeConcreteProductMock();

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

    public function testAddNewItem()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
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

    public function testReduceExistingItem()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
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

    public function testReduceIfLastExisting()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
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

    public function testRemoveItem()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
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

    public function testIncreaseItem()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createProductFacadeConcreteProductMock()
    {
        $concreateProductTransfer = new ConcreteProductTransfer();
        $concreateProductTransfer->setIdAbstractProduct(1);

        $productFacadeMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Product\Business\ProductFacade')
            ->disableOriginalConstructor()
            ->getMock();

        $productFacadeMock->expects($this->any())->method('getConcreteProduct')
            ->will($this->returnValue($concreateProductTransfer));

        return $productFacadeMock;
    }

}
