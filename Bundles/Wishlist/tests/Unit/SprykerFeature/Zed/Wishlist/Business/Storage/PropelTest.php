<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Bundles\Wishlist\tests\Unit\SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\Wishlist\Business\Storage\Propel;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlist;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;

class PropelTest extends \PHPUnit_Framework_TestCase
{
    public function testAddItemToExisting()
    {
        $wishlist = new WishlistTransfer();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer(1);

        $sypWishlist = new WishlistSpy();
        $sypWishlist->setFkCustomer(1);

        $spyWishlistItem = new WishlistItemSpy();
        $spyWishlistItem->setQuantity(1);
        $spyWishlistItem->setFkAbstractProduct(1);
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

    public function testReduceQuantity()
    {
        $wishlist = new WishlistTransfer();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer(1);

        $sypWishlist = new WishlistSpy();
        $sypWishlist->setFkCustomer(1);

        $spyWishlistItem = new WishlistItemSpy();
        $spyWishlistItem->setQuantity(3);
        $spyWishlistItem->setFkAbstractProduct(1);
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

    public function testRemoveItem()
    {
        $wishlist = new WishlistTransfer();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer(1);

        $sypWishlist = new WishlistSpy();
        $sypWishlist->setFkCustomer(1);

        $spyWishlistItem = new WishlistItemSpy();
        $spyWishlistItem->setQuantity(3);
        $spyWishlistItem->setFkAbstractProduct(1);
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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getWishlistQueryContainerMock(
        WishlistSpy $wishlistSpy = null,
        WishlistItemSpy $wishlistItemSpy = null
    ) {
        $wishlistQueryContainerMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainerInterface')
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

    protected function getSpyWishlistQueryMock()
    {
        $spyWishlistMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistQuery')
            ->setMethods(['findOneByFkCustomer'])
            ->disableOriginalConstructor()
            ->getMock();

        return $spyWishlistMock;
    }

    protected function getSpyWishlistItemQueryMock()
    {
        $spyWishlistMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItemQuery')
            ->setMethods(['findOne'])
            ->disableOriginalConstructor()
            ->getMock();

        return $spyWishlistMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCustomerMock($value = null)
    {
        $customerMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Wishlist\Business\Model\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock->expects($this->any())->method('getWishlist')->will($this->returnValue($value));

        return $customerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getProductFacadeMock()
    {
        $productFacadeMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Product\Business\ProductFacade')
            ->disableOriginalConstructor()
            ->getMock();

        $concreteProduct = new ConcreteProductTransfer();
        $concreteProduct->setIdAbstractProduct(1);
        $concreteProduct->setIdConcreteProduct(1);
        $productFacadeMock
            ->expects($this->any())
            ->method('getConcreteProduct')
            ->will($this->returnValue($concreteProduct));

        return $productFacadeMock;
    }
}

trait WishlistSpyTrait {

    protected $delete = true;
    protected $save = true;

    public function save(ConnectionInterface $con = null)
    {
        $this->save = true;
    }
    public function delete(ConnectionInterface $con = null)
    {
        $this->delete = true;
    }

    /**
     * @return boolean
     */
    public function isDelete()
    {
        return $this->delete;
    }

    /**
     * @return boolean
     */
    public function isSave()
    {
        return $this->save;
    }

}

class WishlistItemSpy extends SpyWishlistItem {
    use WishlistSpyTrait;
}

class WishlistSpy extends SpyWishlist {
    use WishlistSpyTrait;
}


