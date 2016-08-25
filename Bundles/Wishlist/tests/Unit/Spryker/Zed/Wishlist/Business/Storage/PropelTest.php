<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Wishlist\Business\Storage;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Wishlist\Business\Model\CustomerInterface;
use Spryker\Zed\Wishlist\Business\Storage\Propel;
use Spryker\Zed\Wishlist\Dependency\Facade\WishlistToProductBridge;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Wishlist
 * @group Business
 * @group Storage
 * @group PropelTest
 */
class PropelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAddItemToExisting()
    {
        $wishlist = new WishlistTransfer();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer(1);

        $sypWishlist = new WishlistSpy();
        $sypWishlist->setFkCustomer(1);

        $spyWishlistItem = new WishlistItemSpy();
        $spyWishlistItem->setQuantity(1);
        $spyWishlistItem->setFkProductAbstract(1);
        $spyWishlistItem->setFkProduct(1);
        $spyWishlistItem->setGroupKey(123);

        $propelStorage = new Propel(
            $this->getWishlistQueryContainerMock($sypWishlist, $spyWishlistItem),
            $this->getCustomerMock(),
            $wishlist,
            $customerTransfer,
            $this->getProductFacadeMock()
        );

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey('123');
        $wishlistItem->setQuantity(1);
        $wishlistChange->addItem($wishlistItem);

        $propelStorage->addItems($wishlistChange);

        $this->assertEquals(2, $spyWishlistItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testAddNewItem()
    {
        $wishlist = new WishlistTransfer();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer(1);

        $sypWishlist = new WishlistSpy();
        $sypWishlist->setFkCustomer(1);

        $spyWishlistItem = new WishlistItemSpy();
        $spyWishlistItem->setQuantity(0);
        $spyWishlistContainerMock = $this->getWishlistQueryContainerMock($sypWishlist, $spyWishlistItem);

        $propelStorage = new Propel(
            $spyWishlistContainerMock,
            $this->getCustomerMock(),
            $wishlist,
            $customerTransfer,
            $this->getProductFacadeMock()
        );

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey('123');
        $wishlistItem->setQuantity(1);
        $wishlistChange->addItem($wishlistItem);

        $propelStorage->addItems($wishlistChange);

        $this->assertEquals(1, $spyWishlistItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testReduceQuantity()
    {
        $wishlist = new WishlistTransfer();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer(1);

        $sypWishlist = new WishlistSpy();
        $sypWishlist->setFkCustomer(1);

        $spyWishlistItem = new WishlistItemSpy();
        $spyWishlistItem->setQuantity(3);
        $spyWishlistItem->setFkProductAbstract(1);
        $spyWishlistItem->setFkProduct(1);
        $spyWishlistItem->setGroupKey(123);

        $propelStorage = new Propel(
            $this->getWishlistQueryContainerMock($sypWishlist, $spyWishlistItem),
            $this->getCustomerMock(),
            $wishlist,
            $customerTransfer,
            $this->getProductFacadeMock()
        );

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey('123');
        $wishlistItem->setQuantity(1);
        $wishlistChange->addItem($wishlistItem);

        $propelStorage->decreaseItems($wishlistChange);

        $this->assertEquals(2, $spyWishlistItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testRemoveItem()
    {
        $wishlist = new WishlistTransfer();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer(1);

        $sypWishlist = new WishlistSpy();
        $sypWishlist->setFkCustomer(1);

        $spyWishlistItem = new WishlistItemSpy();
        $spyWishlistItem->setQuantity(3);
        $spyWishlistItem->setFkProductAbstract(1);
        $spyWishlistItem->setFkProduct(1);
        $spyWishlistItem->setGroupKey(123);

        $propelStorage = new Propel(
            $this->getWishlistQueryContainerMock($sypWishlist, $spyWishlistItem),
            $this->getCustomerMock(),
            $wishlist,
            $customerTransfer,
            $this->getProductFacadeMock()
        );

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey('123');
        $wishlistItem->setQuantity(0);
        $wishlistChange->addItem($wishlistItem);

        $propelStorage->removeItems($wishlistChange);

        $this->assertTrue($spyWishlistItem->isDelete());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface
     */
    protected function getWishlistQueryContainerMock(
        WishlistSpy $wishlistSpy = null,
        WishlistItemSpy $wishlistItemSpy = null
    ) {
        $wishlistQueryContainerMock = $this
            ->getMockBuilder('Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $spyWishlistQueryMock = $this->getSpyWishlistQueryMock();

        $spyWishlistQueryMock
            ->expects($this->any())
            ->method('findOneByFkCustomer')
            ->will($this->returnValue($wishlistSpy));

        $wishlistQueryContainerMock
            ->expects($this->any())
            ->method('queryWishlist')
            ->will($this->returnValue($spyWishlistQueryMock));

        $spyWishlistItemQueryMock = $this->getSpyWishlistItemQueryMock();

        $spyWishlistItemQueryMock
            ->expects($this->any())
            ->method('findOne')
            ->will($this->returnValue($wishlistItemSpy));

        $wishlistQueryContainerMock->expects($this->any())
            ->method('queryCustomerWishlistByGroupKey')
            ->will($this->returnValue($spyWishlistItemQueryMock));

        $wishlistQueryContainerMock->expects($this->any())
            ->method('queryCustomerWishlistByProductId')
            ->will($this->returnValue($spyWishlistItemQueryMock));

        return $wishlistQueryContainerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSpyWishlistQueryMock()
    {
        $spyWishlistMock = $this
            ->getMockBuilder('Orm\Zed\Wishlist\Persistence\SpyWishlistQuery')
            ->setMethods(['findOneByFkCustomer'])
            ->disableOriginalConstructor()
            ->getMock();

        return $spyWishlistMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSpyWishlistItemQueryMock()
    {
        $spyWishlistMock = $this
            ->getMockBuilder('Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery')
            ->setMethods(['findOne'])
            ->disableOriginalConstructor()
            ->getMock();

        return $spyWishlistMock;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer|null $value
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Wishlist\Business\Model\CustomerInterface
     */
    protected function getCustomerMock($value = null)
    {
        $customerMock = $this
            ->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock->expects($this->any())->method('getWishlist')->will($this->returnValue($value));

        return $customerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Wishlist\Dependency\Facade\WishlistToProductInterface
     */
    protected function getProductFacadeMock()
    {
        $productFacadeMock = $this
            ->getMockBuilder(WishlistToProductBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productConcrete = new ProductConcreteTransfer();
        $productConcrete->setIdProductAbstract(1);
        $productConcrete->setIdProductConcrete(1);
        $productFacadeMock
            ->expects($this->any())
            ->method('getProductConcrete')
            ->will($this->returnValue($productConcrete));

        return $productFacadeMock;
    }

}

trait WishlistSpyTrait
{

    /**
     * @var bool
     */
    protected $delete = true;

    /**
     * @var bool
     */
    protected $save = true;

    /**
     * @return void
     */
    public function save(ConnectionInterface $con = null)
    {
        $this->save = true;
    }

    /**
     * @return void
     */
    public function delete(ConnectionInterface $con = null)
    {
        $this->delete = true;
    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        return $this->delete;
    }

    /**
     * @return bool
     */
    public function isSave()
    {
        return $this->save;
    }

}

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Wishlist
 * @group Business
 * @group Storage
 * @group WishlistItemSpy
 */
class WishlistItemSpy extends SpyWishlistItem
{

    use WishlistSpyTrait;

}

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Wishlist
 * @group Business
 * @group Storage
 * @group WishlistSpy
 */
class WishlistSpy extends SpyWishlist
{

    use WishlistSpyTrait;

}
