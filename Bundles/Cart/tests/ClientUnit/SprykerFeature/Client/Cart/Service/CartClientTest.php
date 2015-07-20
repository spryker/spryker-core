<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace ClientUnit\SprykerFeature\Client\Cart\Service;

use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use SprykerEngine\Client\Kernel\Factory;
use SprykerEngine\Client\Kernel\Locator;
use SprykerFeature\Client\Cart\Service\CartClient;
use SprykerFeature\Client\Cart\Service\Session\CartSessionInterface;
use SprykerFeature\Client\Cart\Service\Storage\CartStorageInterface;
use SprykerFeature\Client\Cart\Service\Zed\CartStubInterface;

/**
 * @group SprykerFeature
 * @group Client
 * @group Cart
 * @group Service
 * @group CartClient
 */
class CartClientTest extends \PHPUnit_Framework_TestCase
{

    public function testGetCartMustReturnInstanceOfCartTransfer()
    {
        $cartTransfer = new CartTransfer();
        $sessionMock = $this->getMock('SprykerFeature\Client\Cart\Service\Session\CartSessionInterface', [
            'getCart', 'setCart', 'getItemCount', 'setItemCount'
        ]);
        $sessionMock->expects($this->once())
            ->method('getCart')
            ->will($this->returnValue($cartTransfer))
        ;

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $this->assertSame($cartTransfer, $cartClientMock->getCart());
    }

    public function testClearCartMustSetItemCountInSessionToZero()
    {
        $sessionMock = $this->getMock('SprykerFeature\Client\Cart\Service\Session\CartSessionInterface', [
            'getCart', 'setCart', 'getItemCount', 'setItemCount'
        ]);
        $sessionMock->expects($this->once())
            ->method('setItemCount')
            ->with(0)
            ->will($this->returnValue($sessionMock))
        ;

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $cartClientMock->clearCart();
    }

    public function testClearCartMustSetCartTransferInSessionToAnEmptyInstance()
    {
        $cartTransfer = new CartTransfer();
        $sessionMock = $this->getMock('SprykerFeature\Client\Cart\Service\Session\CartSessionInterface', [
            'getCart', 'setCart', 'getItemCount', 'setItemCount'
        ]);
        $sessionMock->expects($this->once())
            ->method('setItemCount')
            ->will($this->returnValue($sessionMock))
        ;

        $sessionMock->expects($this->once())
            ->method('setCart')
            ->with($cartTransfer)
            ->will($this->returnValue($sessionMock))
        ;

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $cartClientMock->clearCart();
    }

    public function testGetItemCountMustReturnItemCountFromSession()
    {
        $sessionMock = $this->getMock('SprykerFeature\Client\Cart\Service\Session\CartSessionInterface', [
            'getCart', 'setCart', 'getItemCount', 'setItemCount'
        ]);
        $sessionMock->expects($this->once())
            ->method('getItemCount')
            ->will($this->returnValue(0))
        ;

        $dependencyContainerMock = $this->getDependencyContainerMock($sessionMock);
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $this->assertSame(0, $cartClientMock->getItemCount());
    }

    public function testAddItemMustOnlyExceptTransferInterfaceAsArgument()
    {
        $cartItemTransfer = new CartItemTransfer();

        $dependencyContainerMock = $this->getDependencyContainerMock();
        $cartClientMock = $this->getCartClientMock($dependencyContainerMock);

        $cartTransfer = $cartClientMock->addItem($cartItemTransfer);

        $this->assertInstanceOf('Generated\Shared\Cart\CartInterface', $cartTransfer);
    }

    /**
     * @param CartSessionInterface|null $cartSession
     * @param CartStubInterface|null $cartStub
     * @param CartStorageInterface|null $cartStorage
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getDependencyContainerMock(
        CartSessionInterface $cartSession = null,
        CartStubInterface $cartStub = null,
        CartStorageInterface $cartStorage = null
    ) {
        $dependencyContainerMock = $this->getMock(
            'SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer',
            ['createSession', 'createZedStub', 'createStorage'], [], '', false)
        ;

        if (!is_null($cartSession)) {
            $dependencyContainerMock->expects($this->once())
                ->method('createSession')
                ->will($this->returnValue($cartSession))
            ;
        }
        if (!is_null($cartStub)) {
            $dependencyContainerMock->expects($this->once())
                ->method('createZedStub')
                ->will($this->returnValue($cartStub))
            ;
        }
        if (!is_null($cartStorage)) {
            $dependencyContainerMock->expects($this->once())
                ->method('createStorage')
                ->will($this->returnValue($cartStorage))
            ;
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
            'SprykerFeature\Client\Cart\Service\CartClient',
            ['getDependencyContainer'], [], '', false)
        ;

        $cartClientMock->expects($this->once())
            ->method('getDependencyContainer')
            ->will($this->returnValue($dependencyContainerMock));

        return $cartClientMock;
    }

}
