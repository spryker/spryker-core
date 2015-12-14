<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace ClientUnit\SprykerFeature\Client\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use SprykerFeature\Client\Cart\CartClient;
use SprykerFeature\Client\Cart\Session\CartSessionInterface;
use SprykerFeature\Client\Cart\Zed\CartStubInterface;

/**
 * @group SprykerFeature
 * @group Client
 * @group Cart
 * @group Service
 * @group CartClient
 */
class CartClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetCartMustReturnInstanceOfCartTransfer()
    {
        $cartTransfer = new CartTransfer();
        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->once())
            ->method('getCart')
            ->will($this->returnValue($cartTransfer));

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $this->assertSame($cartTransfer, $cartClientMock->getCart());
    }

    /**
     * @return void
     */
    public function testClearCartMustSetItemCountInSessionToZero()
    {
        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->once())
            ->method('setItemCount')
            ->with(0)
            ->will($this->returnValue($sessionMock));

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $cartClientMock->clearCart();
    }

    /**
     * @return void
     */
    public function testClearCartMustSetCartTransferInSessionToAnEmptyInstance()
    {
        $cartTransfer = new CartTransfer();
        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->once())
            ->method('setItemCount')
            ->will($this->returnValue($sessionMock));

        $sessionMock->expects($this->once())
            ->method('setCart')
            ->with($cartTransfer)
            ->will($this->returnValue($sessionMock));

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $cartClientMock->clearCart();
    }

    /**
     * @return void
     */
    public function testGetItemCountMustReturnItemCountFromSession()
    {
        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->once())
            ->method('getItemCount')
            ->will($this->returnValue(0));

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $this->assertSame(0, $cartClientMock->getItemCount());
    }

    /**
     * @return void
     */
    public function testAddItemMustOnlyExceptTransferInterfaceAsArgument()
    {
        $itemTransfer = new ItemTransfer();
        $cartTransfer = new CartTransfer();
        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->once())
            ->method('getCart')
            ->will($this->returnValue($cartTransfer));

        $stubMock = $this->getStubMock();
        $stubMock->expects($this->once())
            ->method('addItem')
            ->will($this->returnValue($cartTransfer));

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $cartTransfer = $cartClientMock->addItem($itemTransfer);

        $this->assertInstanceOf('Generated\Shared\Transfer\CartTransfer', $cartTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveItemMustOnlyExceptItemTransferAsArgument()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setId('identifier');
        $cartTransfer = new CartTransfer();
        $cartTransfer->addItem($itemTransfer);

        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->exactly(2))
            ->method('getCart')
            ->will($this->returnValue($cartTransfer));

        $stubMock = $this->getStubMock();
        $stubMock->expects($this->once())
            ->method('removeItem')
            ->will($this->returnValue($cartTransfer));

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $cartTransfer = $cartClientMock->removeItem($itemTransfer);

        $this->assertInstanceOf('Generated\Shared\Transfer\CartTransfer', $cartTransfer);
    }

    /**
     * @return void
     */
    public function testChangeItemQuantityMustOnlyExceptItemTransferAsArgument()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(2);
        $itemTransfer->setId('identifier');

        $cartTransfer = new CartTransfer();
        $cartTransfer->addItem($itemTransfer);

        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->exactly(3))
            ->method('getCart')
            ->will($this->returnValue($cartTransfer));

        $stubMock = $this->getStubMock();
        $stubMock->expects($this->once())
            ->method('decreaseItemQuantity')
            ->will($this->returnValue($cartTransfer));
        $stubMock->expects($this->never())
            ->method('increaseItemQuantity')
            ->will($this->returnValue($cartTransfer));

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(1);
        $itemTransfer->setId('identifier');

        $cartTransfer = $cartClientMock->changeItemQuantity($itemTransfer);

        $this->assertInstanceOf('Generated\Shared\Transfer\CartTransfer', $cartTransfer);
    }

    /**
     * @return void
     */
    public function testChangeItemQuantityMustCallDecreaseItemQuantityWhenPassedItemQuantityIsLowerThenInCartGivenItem()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(2);
        $itemTransfer->setId('identifier');

        $cartTransfer = new CartTransfer();
        $cartTransfer->addItem($itemTransfer);

        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->exactly(3))
            ->method('getCart')
            ->will($this->returnValue($cartTransfer));

        $stubMock = $this->getStubMock();
        $stubMock->expects($this->once())
            ->method('decreaseItemQuantity')
            ->will($this->returnValue($cartTransfer));
        $stubMock->expects($this->never())
            ->method('increaseItemQuantity')
            ->will($this->returnValue($cartTransfer));

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setId('identifier');

        $cartTransfer = $cartClientMock->changeItemQuantity($itemTransfer, 1);

        $this->assertInstanceOf('Generated\Shared\Transfer\CartTransfer', $cartTransfer);
    }

    /**
     * @return void
     */
    public function testChangeItemQuantityMustCallIncreaseItemQuantityWhenPassedItemQuantityIsLowerThenInCartGivenItem()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(1);
        $itemTransfer->setId('identifier');

        $cartTransfer = new CartTransfer();
        $cartTransfer->addItem($itemTransfer);

        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->exactly(3))
            ->method('getCart')
            ->will($this->returnValue($cartTransfer));

        $stubMock = $this->getStubMock();
        $stubMock->expects($this->never())
            ->method('decreaseItemQuantity')
            ->will($this->returnValue($cartTransfer));
        $stubMock->expects($this->once())
            ->method('increaseItemQuantity')
            ->will($this->returnValue($cartTransfer));

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setId('identifier');

        $cartTransfer = $cartClientMock->changeItemQuantity($itemTransfer, 2);

        $this->assertInstanceOf('Generated\Shared\Transfer\CartTransfer', $cartTransfer);
    }

    /**
     * @param CartSessionInterface|null $cartSession
     * @param CartStubInterface|null $cartStub
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getDependencyContainerMock(
        CartSessionInterface $cartSession = null,
        CartStubInterface $cartStub = null
    ) {
        $dependencyContainerMock = $this->getMock(
            'SprykerEngine\Client\Kernel\AbstractDependencyContainer',
            ['createSession', 'createZedStub'], [], '', false);

        if ($cartSession !== null) {
            $dependencyContainerMock->expects($this->any())
                ->method('createSession')
                ->will($this->returnValue($cartSession));
        }
        if ($cartStub !== null) {
            $dependencyContainerMock->expects($this->any())
                ->method('createZedStub')
                ->will($this->returnValue($cartStub));
        }

        return $dependencyContainerMock;
    }

    /**
     * @param $dependencyContainerMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|CartClient
     */
    private function getCartClientMock($dependencyContainerMock)
    {
        $cartClientMock = $this->getMock(
            'SprykerFeature\Client\Cart\CartClient',
            ['getDependencyContainer'], [], '', false);

        $cartClientMock->expects($this->any())
            ->method('getDependencyContainer')
            ->will($this->returnValue($dependencyContainerMock));

        return $cartClientMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSessionMock()
    {
        $sessionMock = $this->getMock('SprykerFeature\Client\Cart\Session\CartSessionInterface', [
            'getCart',
            'setCart',
            'getItemCount',
            'setItemCount',
        ]);

        return $sessionMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CartStubInterface
     */
    private function getStubMock()
    {
        return $this->getMock('SprykerFeature\Client\Cart\Zed\CartStubInterface', [
            'addItem',
            'removeItem',
            'increaseItemQuantity',
            'decreaseItemQuantity',
            'addCoupon',
            'removeCoupon',
            'clearCoupons',
            'recalculate',
        ]);
    }

}
