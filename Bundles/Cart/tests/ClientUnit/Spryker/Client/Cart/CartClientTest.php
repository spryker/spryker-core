<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ClientUnit\Spryker\Client\Cart;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\Cart\CartClient;
use Spryker\Client\Cart\Session\CartSessionInterface;
use Spryker\Client\Cart\Zed\CartStubInterface;

/**
 * @group Spryker
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

        $factoryMock = $this->getFactoryMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

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

        $factoryMock = $this->getFactoryMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

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

        $factoryMock = $this->getFactoryMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

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

        $factoryMock = $this->getFactoryMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

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

        $factoryMock = $this->getFactoryMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

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

        $factoryMock = $this->getFactoryMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

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

        $factoryMock = $this->getFactoryMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

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

        $factoryMock = $this->getFactoryMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

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

        $factoryMock = $this->getFactoryMock($sessionMock, $stubMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setId('identifier');

        $cartTransfer = $cartClientMock->changeItemQuantity($itemTransfer, 2);

        $this->assertInstanceOf('Generated\Shared\Transfer\CartTransfer', $cartTransfer);
    }

    /**
     * @param \Spryker\Client\Cart\Session\CartSessionInterface|null $cartSession
     * @param \Spryker\Client\Cart\Zed\CartStubInterface|null $cartStub
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getFactoryMock(
        CartSessionInterface $cartSession = null,
        CartStubInterface $cartStub = null
    ) {
        $factoryMock = $this->getMock(
            'Spryker\Client\Kernel\AbstractFactory',
            ['createSession', 'createZedStub'],
            [],
            '',
            false
        );

        if ($cartSession !== null) {
            $factoryMock->expects($this->any())
                ->method('createSession')
                ->will($this->returnValue($cartSession));
        }
        if ($cartStub !== null) {
            $factoryMock->expects($this->any())
                ->method('createZedStub')
                ->will($this->returnValue($cartStub));
        }

        return $factoryMock;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $factoryMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Cart\CartClient
     */
    private function getCartClientMock($factoryMock)
    {
        $cartClientMock = $this->getMock(
            'Spryker\Client\Cart\CartClient',
            ['getFactory'],
            [],
            '',
            false
        );

        $cartClientMock->expects($this->any())
            ->method('getFactory')
            ->will($this->returnValue($factoryMock));

        return $cartClientMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSessionMock()
    {
        $sessionMock = $this->getMock('Spryker\Client\Cart\Session\CartSessionInterface', [
            'getCart',
            'setCart',
            'getItemCount',
            'setItemCount',
        ]);

        return $sessionMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Cart\Zed\CartStubInterface
     */
    private function getStubMock()
    {
        return $this->getMock('Spryker\Client\Cart\Zed\CartStubInterface', [
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
