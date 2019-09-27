<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartChangeRequestExpander\CartChangeRequestExpander;
use Spryker\Client\Cart\CartFactory;
use Spryker\Client\Cart\Dependency\Client\CartToQuoteBridge;
use Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin;
use Spryker\Client\Cart\Zed\CartStub;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Cart
 * @group UpdateQuantityCartClientTest
 * Add your own group annotations below this line
 */
class UpdateQuantityCartClientTest extends Unit
{
    protected const FAKE_SKU_1 = 'fake_sku_1';
    protected const FAKE_SKU_2 = 'fake_sku_2';
    protected const FAKE_SKU_3 = 'fake_sku_3';

    /**
     * @return void
     */
    public function testUpdateQuantityDecreasesQuantityForProvidedItems(): void
    {
        // Arrange
        $originalQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $newQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(2)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(1)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setQuantity(2)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(1)->setSku(static::FAKE_SKU_2));

        $cartStubMock = $this->createCartStubMock();

        $cartStubMock
            ->method('removeFromCart')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(true)->setQuoteTransfer($newQuoteTransfer));

        $sessionQuoteStorageStrategyPluginMock = $this->createSessionQuoteStorageStrategyPluginMock(
            $originalQuoteTransfer,
            $this->createCartFactoryMock($originalQuoteTransfer, $cartStubMock)
        );

        // Act
        $quoteResponseTransfer = $sessionQuoteStorageStrategyPluginMock->updateQuantity($cartChangeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($newQuoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityIncreasesQuantityForProvidedItems(): void
    {
        // Arrange
        $originalQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $newQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(7)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setQuantity(7)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2));

        $cartStubMock = $this->createCartStubMock();

        $cartStubMock
            ->method('addToCart')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(true)->setQuoteTransfer($newQuoteTransfer));

        $sessionQuoteStorageStrategyPluginMock = $this->createSessionQuoteStorageStrategyPluginMock(
            $originalQuoteTransfer,
            $this->createCartFactoryMock($originalQuoteTransfer, $cartStubMock)
        );

        // Act
        $quoteResponseTransfer = $sessionQuoteStorageStrategyPluginMock->updateQuantity($cartChangeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($newQuoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityChangesQuantityForProvidedItems(): void
    {
        // Arrange
        $originalQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $newQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(1)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setQuantity(1)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2));

        $cartStubMock = $this->createCartStubMock();

        $cartStubMock
            ->method('addToCart')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(true)->setQuoteTransfer($newQuoteTransfer));

        $cartStubMock
            ->method('removeFromCart')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(true)->setQuoteTransfer($newQuoteTransfer));

        $sessionQuoteStorageStrategyPluginMock = $this->createSessionQuoteStorageStrategyPluginMock(
            $originalQuoteTransfer,
            $this->createCartFactoryMock($originalQuoteTransfer, $cartStubMock)
        );

        // Act
        $quoteResponseTransfer = $sessionQuoteStorageStrategyPluginMock->updateQuantity($cartChangeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($newQuoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityChangesQuantityForEmptyCartChangeTransfer(): void
    {
        // Arrange
        $originalQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $sessionQuoteStorageStrategyPluginMock = $this->createSessionQuoteStorageStrategyPluginMock(
            $originalQuoteTransfer,
            $this->createCartFactoryMock($originalQuoteTransfer, $this->createCartStubMock())
        );

        // Act
        $quoteResponseTransfer = $sessionQuoteStorageStrategyPluginMock->updateQuantity(new CartChangeTransfer());

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($originalQuoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityChangesQuantityForSameCartChangeTransfer(): void
    {
        // Arrange
        $originalQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $sessionQuoteStorageStrategyPluginMock = $this->createSessionQuoteStorageStrategyPluginMock(
            $originalQuoteTransfer,
            $this->createCartFactoryMock($originalQuoteTransfer, $this->createCartStubMock())
        );

        // Act
        $quoteResponseTransfer = $sessionQuoteStorageStrategyPluginMock->updateQuantity($cartChangeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($originalQuoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityUpdatesQuantityWithoutIncreasing(): void
    {
        // Arrange
        $originalQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setQuantity(1)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2));

        $cartStubMock = $this->createCartStubMock();

        $cartStubMock
            ->method('addToCart')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(false)->setQuoteTransfer($originalQuoteTransfer));

        $sessionQuoteStorageStrategyPluginMock = $this->createSessionQuoteStorageStrategyPluginMock(
            $originalQuoteTransfer,
            $this->createCartFactoryMock($originalQuoteTransfer, $cartStubMock)
        );

        // Act
        $quoteResponseTransfer = $sessionQuoteStorageStrategyPluginMock->updateQuantity($cartChangeTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($originalQuoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityUpdatesQuantityWithoutDecreasing(): void
    {
        // Arrange
        $originalQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $newQuoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setQuantity(1)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2));

        $cartStubMock = $this->createCartStubMock();

        $cartStubMock
            ->method('addToCart')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(true)->setQuoteTransfer($newQuoteTransfer));

        $cartStubMock
            ->method('removeFromCart')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(false)->setQuoteTransfer($newQuoteTransfer));

        $sessionQuoteStorageStrategyPluginMock = $this->createSessionQuoteStorageStrategyPluginMock(
            $originalQuoteTransfer,
            $this->createCartFactoryMock($originalQuoteTransfer, $cartStubMock)
        );

        // Act
        $quoteResponseTransfer = $sessionQuoteStorageStrategyPluginMock->updateQuantity($cartChangeTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($originalQuoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Cart\CartFactory $cartFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin
     */
    protected function createSessionQuoteStorageStrategyPluginMock(QuoteTransfer $quoteTransfer, $cartFactoryMock)
    {
        $sessionQuoteStorageStrategyPluginMock = $this->getMockBuilder(SessionQuoteStorageStrategyPlugin::class)
            ->setMethods([
                'findItem',
                'getFactory',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $sessionQuoteStorageStrategyPluginMock
            ->method('findItem')
            ->willReturnCallback(function ($sku) use ($quoteTransfer) {
                foreach ($quoteTransfer->getItems() as $itemTransfer) {
                    if ($itemTransfer->getSku() === $sku) {
                        return $itemTransfer;
                    }
                }

                return null;
            });

        $sessionQuoteStorageStrategyPluginMock
            ->method('getFactory')
            ->willReturn($cartFactoryMock);

        return $sessionQuoteStorageStrategyPluginMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Cart\Zed\CartStub $cartStubMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Cart\CartFactory
     */
    protected function createCartFactoryMock(QuoteTransfer $quoteTransfer, $cartStubMock)
    {
        $cartFactoryMock = $this
            ->getMockBuilder(CartFactory::class)
            ->setMethods([
                'createCartChangeRequestExpander',
                'createZedStub',
                'getQuoteClient',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $cartFactoryMock
            ->method('createCartChangeRequestExpander')
            ->willReturn($this->createCartChangeRequestExpanderMock());

        $cartFactoryMock
            ->method('createZedStub')
            ->willReturn($cartStubMock);

        $cartFactoryMock
            ->method('getQuoteClient')
            ->willReturn($this->createQuoteClientMock($quoteTransfer));

        return $cartFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Cart\CartChangeRequestExpander\CartChangeRequestExpander
     */
    protected function createCartChangeRequestExpanderMock()
    {
        $cartChangeRequestExpanderMock = $this
            ->getMockBuilder(CartChangeRequestExpander::class)
            ->setMethods([
                'addItemsRequestExpand',
                'removeItemRequestExpand',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $cartChangeRequestExpanderMock
            ->method('addItemsRequestExpand')
            ->willReturnCallback(function (CartChangeTransfer $cartChangeTransfer) {
                return $cartChangeTransfer;
            });

        $cartChangeRequestExpanderMock
            ->method('removeItemRequestExpand')
            ->willReturnCallback(function (CartChangeTransfer $cartChangeTransfer) {
                return $cartChangeTransfer;
            });

        return $cartChangeRequestExpanderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Cart\Zed\CartStub
     */
    protected function createCartStubMock()
    {
        $cartStubMock = $this->getMockBuilder(CartStub::class)
            ->setMethods([
                'addToCart',
                'removeFromCart',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        return $cartStubMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Cart\Dependency\Client\CartToQuoteBridge
     */
    protected function createQuoteClientMock(QuoteTransfer $quoteTransfer)
    {
        $quoteClientMock = $this->getMockBuilder(CartToQuoteBridge::class)
            ->setMethods([
                'getQuote',
                'setQuote',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteClientMock
            ->method('getQuote')
            ->willReturn($quoteTransfer);

        $quoteClientMock
            ->method('setQuote')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return $quoteTransfer;
            });

        return $quoteClientMock;
    }
}
