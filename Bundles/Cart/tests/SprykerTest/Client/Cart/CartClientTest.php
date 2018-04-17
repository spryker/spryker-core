<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartChangeRequestExpander\CartChangeRequestExpander;
use Spryker\Client\Cart\CartClient;
use Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface;
use Spryker\Client\Cart\Plugin\ItemCountPlugin;
use Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin;
use Spryker\Client\Cart\Plugin\SimpleProductQuoteItemFinderPlugin;
use Spryker\Client\Cart\Zed\CartStubInterface;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Cart
 * @group CartClientTest
 * Add your own group annotations below this line
 */
class CartClientTest extends Unit
{
    /**
     * @return void
     */
    public function testGetCartMustReturnInstanceOfQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteMock = $this->getQuoteMock();
        $quoteMock->expects($this->once())
            ->method('getQuote')
            ->will($this->returnValue($quoteTransfer));

        $factoryMock = $this->getFactoryMock($quoteMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

        $this->assertSame($quoteTransfer, $cartClientMock->getQuote());
    }

    /**
     * @return void
     */
    public function testClearCartMustSetItemCountInSessionToZero()
    {
        $quoteMock = $this->getQuoteMock();
        $quoteMock->expects($this->once())
            ->method('clearQuote')
            ->will($this->returnValue($quoteMock));

        $factoryMock = $this->getFactoryMock($quoteMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

        $cartClientMock->clearQuote();
    }

    /**
     * @return void
     */
    public function testClearCartMustSetCartTransferInSessionToAnEmptyInstance()
    {
        $quoteMock = $this->getQuoteMock();
        $quoteMock->expects($this->once())
            ->method('clearQuote')
            ->will($this->returnValue($quoteMock));

        $factoryMock = $this->getFactoryMock($quoteMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

        $cartClientMock->clearQuote();
    }

    /**
     * @return void
     */
    public function testAddItemMustOnlyExceptTransferInterfaceAsArgument()
    {
        $itemTransfer = new ItemTransfer();
        $quoteTransfer = new QuoteTransfer();
        $quoteMock = $this->getQuoteMock();
        $quoteMock->expects($this->once())
            ->method('getQuote')
            ->will($this->returnValue($quoteTransfer));

        $stubMock = $this->getStubMock();
        $stubMock->expects($this->once())
            ->method('addItem')
            ->will($this->returnValue($quoteTransfer));

        $sessionQuoteStorageStrategyPluginMock = $this->getSessionQuoteStorageStrategyPluginMock();
        $factoryMock = $this->getFactoryMock($quoteMock, $stubMock, $sessionQuoteStorageStrategyPluginMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

        $quoteTransfer = $cartClientMock->addItem($itemTransfer);

        $this->assertInstanceOf('Generated\Shared\Transfer\QuoteTransfer', $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testChangeItemQuantityMustCallRemoveItemQuantityWhenPassedItemQuantityIsLowerThenInCartGivenItem()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(2);
        $itemTransfer->setSku('sku');

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        $quoteMock = $this->getQuoteMock();
        $quoteMock->expects($this->exactly(3))
            ->method('getQuote')
            ->will($this->returnValue($quoteTransfer));

        $stubMock = $this->getStubMock();
        $stubMock->expects($this->once())
            ->method('removeItem')
            ->will($this->returnValue($quoteTransfer));
        $stubMock->expects($this->never())
            ->method('addItem')
            ->will($this->returnValue($quoteTransfer));

        $sessionQuoteStorageStrategyPluginMock = $this->getSessionQuoteStorageStrategyPluginMock();
        $factoryMock = $this->getFactoryMock($quoteMock, $stubMock, $sessionQuoteStorageStrategyPluginMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku('sku');

        $quoteTransfer = $cartClientMock->changeItemQuantity('sku', null, 1);

        $this->assertInstanceOf('Generated\Shared\Transfer\QuoteTransfer', $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testChangeItemQuantityMustCallAddItemQuantityWhenPassedItemQuantityIsLowerThenInCartGivenItem()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(1);
        $itemTransfer->setSku('sku');

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        $quoteMock = $this->getQuoteMock();
        $quoteMock->expects($this->exactly(3))
            ->method('getQuote')
            ->will($this->returnValue($quoteTransfer));

        $stubMock = $this->getStubMock();
        $stubMock->expects($this->never())
            ->method('removeItem')
            ->will($this->returnValue($quoteTransfer));

        $stubMock->expects($this->once())
            ->method('addItem')
            ->will($this->returnValue($quoteTransfer));
        $sessionQuoteStorageStrategyPluginMock = $this->getSessionQuoteStorageStrategyPluginMock();
        $factoryMock = $this->getFactoryMock($quoteMock, $stubMock, $sessionQuoteStorageStrategyPluginMock);
        $cartClientMock = $this->getCartClientMock($factoryMock);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku('sku');

        $quoteTransfer = $cartClientMock->changeItemQuantity('sku', null, 2);

        $this->assertInstanceOf('Generated\Shared\Transfer\QuoteTransfer', $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testGetItemCountReturnNumberOfItemsInCart()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(1);
        $itemTransfer->setSku('sku');

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        $mockBuilder = $this->getMockBuilder(CartClient::class);
        $mockBuilder->setMethods(['getQuote', 'getItemCounter']);
        $cartClientMock = $mockBuilder->getMock();
        $cartClientMock->method('getQuote')->willReturn($quoteTransfer);
        $cartClientMock->method('getItemCounter')->willReturn(new ItemCountPlugin());

        $this->assertSame(1, $cartClientMock->getItemCount());
    }

    /**
     * @param \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface|null $quote
     * @param \Spryker\Client\Cart\Zed\CartStubInterface|null $cartStub
     * @param \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface|null $quoteStorageStrategyPlugin
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getFactoryMock(
        CartToQuoteInterface $quote = null,
        CartStubInterface $cartStub = null,
        QuoteStorageStrategyPluginInterface $quoteStorageStrategyPlugin = null
    ) {
        $factoryMock = $this->getMockBuilder(AbstractFactory::class)
            ->setMethods(['getQuoteClient', 'createZedStub', 'getQuoteStorageStrategy', 'createCartChangeRequestExpander', 'getQuoteItemFinderPlugin'])
            ->disableOriginalConstructor()->getMock();

        if ($quote !== null) {
            $factoryMock->expects($this->any())
                ->method('getQuoteClient')
                ->will($this->returnValue($quote));
        }
        if ($cartStub !== null) {
            $factoryMock->expects($this->any())
                ->method('createZedStub')
                ->will($this->returnValue($cartStub));
        }
        if ($cartStub !== null) {
            $quoteStorageStrategyPlugin->expects($this->any())
                ->method('getFactory')
                ->will($this->returnValue($factoryMock));
            $factoryMock->expects($this->any())
                ->method('getQuoteStorageStrategy')
                ->will($this->returnValue($quoteStorageStrategyPlugin));
        }

        $factoryMock->expects($this->any())
            ->method('createCartChangeRequestExpander')
            ->will($this->returnValue(new CartChangeRequestExpander([], [])));

        $factoryMock->expects($this->any())
            ->method('getQuoteItemFinderPlugin')
            ->will($this->returnValue(new SimpleProductQuoteItemFinderPlugin()));

        return $factoryMock;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $factoryMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface
     */
    private function getSessionQuoteStorageStrategyPluginMock()
    {
        $sessionQuoteStorageStrategyPluginMock = $this->getMockBuilder(SessionQuoteStorageStrategyPlugin::class)
            ->setMethods(['getFactory'])->disableOriginalConstructor()->getMock();

        return $sessionQuoteStorageStrategyPluginMock;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $factoryMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Cart\CartClient
     */
    private function getCartClientMock($factoryMock)
    {
        $cartClientMock = $this->getMockBuilder(CartClient::class)->setMethods(['getFactory'])->disableOriginalConstructor()->getMock();

        $cartClientMock->expects($this->any())
            ->method('getFactory')
            ->will($this->returnValue($factoryMock));

        return $cartClientMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getQuoteMock()
    {
        $quoteMock = $this->getMockBuilder(CartToQuoteInterface::class)->setMethods([
            'getQuote',
            'setQuote',
            'clearQuote',
            'getStorageStrategy',
            'reloadItems',
        ])->getMock();

        $quoteMock->method('getStorageStrategy')
            ->will($this->returnValue('session'));

        return $quoteMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Cart\Zed\CartStubInterface
     */
    private function getStubMock()
    {
        return $this->getMockBuilder(CartStubInterface::class)->setMethods([
            'addValidItems',
            'addItem',
            'removeItem',
            'reloadItems',
            'changeItemQuantity',
            'validateQuote',
            'addFlashMessagesFromLastZedRequest',
        ])->getMock();
    }
}
