<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartCode\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCode\CartCodeDependencyProvider;
use Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface;
use Spryker\Zed\Discount\Communication\Plugin\CartCode\VoucherCartCodePlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\CartCode\GiftCardCartCodePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartCode
 * @group Business
 * @group Facade
 * @group CartCodeFacadeTest
 * Add your own group annotations below this line
 */
class CartCodeFacadeTest extends Unit
{
    protected const CODE = 'testCode1';

    /**
     * @var \SprykerTest\Zed\CartCode\CartCodeBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(CartCodeDependencyProvider::PLUGINS_CART_CODE, [
            new VoucherCartCodePlugin(),
            new GiftCardCartCodePlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testAddCartCodeAddsVoucherCodeToUnlockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer(false);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->addCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode(static::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertSame(1, $cartCodeResponseTransfer->getQuote()->getVoucherDiscounts()->count());
        $this->assertEquals(
            static::CODE,
            $cartCodeResponseTransfer->getQuote()->getVoucherDiscounts()->getIterator()->current()->getVoucherCode()
        );
    }

    /**
     * @return void
     */
    public function testAddCartCodeAddsGiftCardCodeToUnlockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer(false);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->addCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode(static::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertSame(1, $cartCodeResponseTransfer->getQuote()->getGiftCards()->count());
        $this->assertEquals(
            static::CODE,
            $cartCodeResponseTransfer->getQuote()->getGiftCards()->getIterator()->current()->getCode()
        );
    }

    /**
     * @return void
     */
    public function testAddCartCodeWillReturnUnsuccessfulResponseWithFailedCartCodePlugin(): void
    {
        // Arrange
        $this->setPluginCartCodeCollection([
            $this->createCartCodePluginInterfaceMockWithOperationResponseMessage('error'),
            $this->createCartCodePluginInterfaceMockWithOperationResponseMessage('success'),
        ]);
        $quoteTransfer = $this->tester->prepareQuoteTransfer(false);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->addCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode(static::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertFalse($cartCodeResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAddCartCodeDoesNotAddVoucherCodeToLockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer(true);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->addCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode(static::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertSame(0, $cartCodeResponseTransfer->getQuote()->getVoucherDiscounts()->count());
    }

    /**
     * @return void
     */
    public function testRemoveCodeRemovesVoucherDiscountFromCartWithUnlockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferWithDiscount(false, static::CODE);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->removeCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode(static::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertSame(0, $cartCodeResponseTransfer->getQuote()->getVoucherDiscounts()->count());
    }

    /**
     * @return void
     */
    public function testRemoveCodeRemovesGiftCardFromCartWithUnlockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferWithDiscount(false, static::CODE);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->removeCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode(static::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertSame(0, $cartCodeResponseTransfer->getQuote()->getGiftCards()->count());
    }

    /**
     * @return void
     */
    public function testRemoveCodeWillReturnUnsuccessfulResponseWithFailedCartCodePlugin(): void
    {
        // Arrange
        $this->setPluginCartCodeCollection([
            $this->createCartCodePluginInterfaceMockWithOperationResponseMessage('error'),
            $this->createCartCodePluginInterfaceMockWithOperationResponseMessage('success'),
        ]);
        $quoteTransfer = $this->tester->prepareQuoteTransferWithDiscount(false, static::CODE);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->removeCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode(static::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertFalse($cartCodeResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testRemoveCodeWillNotRemoveDiscountFromCartWithLockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferWithDiscount(true, static::CODE);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->removeCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode(static::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertSame(1, $cartCodeResponseTransfer->getQuote()->getVoucherDiscounts()->count());
    }

    /**
     * @return void
     */
    public function testClearCartCodesWillRemoveAllDiscountsCodeFromUnlockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferWithDiscount(false, static::CODE);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->clearCartCodes(
            (new CartCodeRequestTransfer())->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertSame(0, $cartCodeResponseTransfer->getQuote()->getVoucherDiscounts()->count());
    }

    /**
     * @return void
     */
    public function testClearCartCodesRemovesAllGiftCardsCodeFromUnlockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferWithDiscount(false, static::CODE);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->clearCartCodes(
            (new CartCodeRequestTransfer())->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertSame(0, $cartCodeResponseTransfer->getQuote()->getGiftCards()->count());
    }

    /**
     * @return void
     */
    public function testClearCartCodesWillNotRemoveAllDiscountsCodeFromLockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferWithDiscount(true, static::CODE);

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->clearCartCodes(
            (new CartCodeRequestTransfer())->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertSame(1, $cartCodeResponseTransfer->getQuote()->getVoucherDiscounts()->count());
    }

    /**
     * @param \Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[] $pluginStack
     *
     * @return void
     */
    protected function setPluginCartCodeCollection(array $pluginStack): void
    {
        $this->tester->setDependency(CartCodeDependencyProvider::PLUGINS_CART_CODE, $pluginStack);
    }

    /**
     * @param string $messageType
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface
     */
    protected function createCartCodePluginInterfaceMockWithOperationResponseMessage(string $messageType): CartCodePluginInterface
    {
        $cartCodePluginInterfaceMock = $this
            ->getMockBuilder(CartCodePluginInterface::class)
            ->setMethods([
                'addCartCode',
                'removeCartCode',
                'clearCartCodes',
                'findOperationResponseMessage',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $cartCodePluginInterfaceMock
            ->method('addCartCode')
            ->willReturn((new QuoteTransfer()));
        $cartCodePluginInterfaceMock
            ->method('removeCartCode')
            ->willReturn((new QuoteTransfer()));
        $cartCodePluginInterfaceMock
            ->method('clearCartCodes')
            ->willReturn((new QuoteTransfer()));
        $cartCodePluginInterfaceMock
            ->method('findOperationResponseMessage')
            ->willReturn((new MessageTransfer())->setType($messageType));

        return $cartCodePluginInterfaceMock;
    }
}
