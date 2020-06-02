<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartCodesRestApi\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Shared\CartCodesRestApi\CartCodesRestApiConfig;
use Spryker\Zed\CartCode\Business\CartCodeFacade;
use Spryker\Zed\CartCode\CartCodeDependencyProvider;
use Spryker\Zed\CartCodesRestApi\Business\CartCodesRestApiBusinessFactory;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeBridge;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeBridge;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface;
use Spryker\Zed\Discount\Communication\Plugin\CartCode\VoucherCartCodePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartCodesRestApi
 * @group Business
 * @group Facade
 * @group CartCodesRestApiFacadeTest
 * Add your own group annotations below this line
 */
class CartCodesRestApiFacadeTest extends Test
{
    protected const CODE = 'testCode1';

    /**
     * @uses \Spryker\Shared\CartsRestApi\CartsRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND
     */
    protected const ERROR_IDENTIFIER_CART_NOT_FOUND = 'ERROR_IDENTIFIER_CART_NOT_FOUND';

    /**
     * @var \SprykerTest\Zed\CartCodesRestApi\CartCodesRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CartCodesRestApi\Business\CartCodesRestApiFacadeInterface
     */
    protected $cartCodesRestApiFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setPluginCartCodeCollection();

        /** @var \Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeBridge $mockCartsRestApiFacade */
        $mockCartsRestApiFacade = $this->createMockCartsRestApiFacade();

        /** @var \Spryker\Zed\CartCodesRestApi\Business\CartCodesRestApiBusinessFactory $mockCartCodesRestApiBusinessFactory */
        $mockCartCodesRestApiBusinessFactory = $this->createMockCartCodesRestApiBusinessFactory($mockCartsRestApiFacade);

        $this->cartCodesRestApiFacade = $this->tester->getFacade();
        $this->cartCodesRestApiFacade->setFactory($mockCartCodesRestApiBusinessFactory);
    }

    /**
     * @return void
     */
    public function testAddCartCodeWillAddCodeWithExistingQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->havePersistentQuoteWithoutVouchers();

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->addCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode($this->tester::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertEquals(1, $cartCodeResponseTransfer->getQuote()->getVoucherDiscounts()->count());
        $this->assertEquals(
            $this->tester::CODE,
            $cartCodeResponseTransfer->getQuote()->getVoucherDiscounts()[0]->getVoucherCode()
        );
    }

    /**
     * @return void
     */
    public function testAddCartCodeWillNotAddCodeWithNonExistentQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->addCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode($this->tester::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertEquals(
            CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND,
            $cartCodeResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testRemoveCartCodeWillRemoveDiscountWithExistingQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithVouchers();

        // Act
        $cartCodeResponseTransfer = $this->cartCodesRestApiFacade->removeCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode($this->tester::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertEmpty($cartCodeResponseTransfer->getQuote()->getVoucherDiscounts());
    }

    /**
     * @return void
     */
    public function testRemoveCartCodeWillNotRemoveDiscountWithNonExistentCartCode(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->havePersistentQuoteWithVouchers();

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->removeCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode($this->tester::NON_EXISTENT_CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertEmpty($cartCodeResponseTransfer->getQuote());
        $this->assertEquals(
            CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_CODE_NOT_FOUND,
            $cartCodeResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testRemoveCartCodeWillNotRemoveDiscountWithNonExistentQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->removeCartCode(
            (new CartCodeRequestTransfer())
                ->setCartCode($this->tester::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertEmpty($cartCodeResponseTransfer->getQuote());
        $this->assertEquals(
            CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND,
            $cartCodeResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testRemoveCartCodeFromQuoteRemovesDiscountCodeWithExistingQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithVouchers();

        // Act
        $cartCodeResponseTransfer = $this->cartCodesRestApiFacade->removeCartCodeFromQuote(
            (new CartCodeRequestTransfer())
                ->setCartCode($this->tester::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertTrue($cartCodeResponseTransfer->getIsSuccessful());
        $this->assertEmpty($cartCodeResponseTransfer->getQuote()->getVoucherDiscounts());
    }

    /**
     * @return void
     */
    public function testRemoveCartCodeFromQuoteRemovesGiftCardCodeWithExistingQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithGiftCard();

        // Act
        $cartCodeResponseTransfer = $this->cartCodesRestApiFacade->removeCartCodeFromQuote(
            (new CartCodeRequestTransfer())
                ->setCartCode($this->tester::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertTrue($cartCodeResponseTransfer->getIsSuccessful());
        $this->assertEmpty($cartCodeResponseTransfer->getQuote()->getGiftCards());
    }

    /**
     * @return void
     */
    public function testRemoveCartCodeFromQuoteNotRemovesCodeWithNonExistentQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $cartCodeResponseTransfer = $this->tester->getFacade()->removeCartCodeFromQuote(
            (new CartCodeRequestTransfer())
                ->setCartCode($this->tester::CODE)
                ->setQuote($quoteTransfer)
        );

        // Assert
        $this->assertEmpty($cartCodeResponseTransfer->getQuote());
        $this->assertEquals(
            CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND,
            $cartCodeResponseTransfer->getMessages()->getIterator()->current()->getValue()
        );
    }

    /**
     * @return void
     */
    protected function setPluginCartCodeCollection(): void
    {
        $this->tester->setDependency(CartCodeDependencyProvider::PLUGINS_CART_CODE, [
            new VoucherCartCodePlugin(),
        ]);
    }

    /**
     * @return \Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockCartsRestApiFacade(): CartCodesRestApiToCartsRestApiFacadeInterface
    {
        $mockCartsRestApiFacade = $this->getMockBuilder(CartCodesRestApiToCartsRestApiFacadeBridge::class)
            ->disableOriginalConstructor()
            ->setMethods(['findQuoteByUuid'])
            ->getMock();
        $mockCartsRestApiFacade->method('findQuoteByUuid')
            ->willReturn(
                (new QuoteResponseTransfer())
                    ->setQuoteTransfer($this->tester->createQuoteTransferWithVouchers())
                    ->setIsSuccessful(true)
            );

        return $mockCartsRestApiFacade;
    }

    /**
     * @param \Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeBridge $mockCartsRestApiFacade
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CartCodesRestApi\Business\CartCodesRestApiBusinessFactory
     */
    protected function createMockCartCodesRestApiBusinessFactory(
        CartCodesRestApiToCartsRestApiFacadeBridge $mockCartsRestApiFacade
    ): CartCodesRestApiBusinessFactory {
        $mockCartCodesRestApiBusinessFactory = $this->getMockBuilder(CartCodesRestApiBusinessFactory::class)
            ->setMethods(['getCartsRestApiFacade', 'getCartCodeFacade'])
            ->getMock();

        $mockCartCodesRestApiBusinessFactory->method('getCartsRestApiFacade')
            ->willReturn($mockCartsRestApiFacade);

        $mockCartCodesRestApiBusinessFactory->method('getCartCodeFacade')
            ->willReturn(new CartCodesRestApiToCartCodeFacadeBridge(new CartCodeFacade()));

        return $mockCartCodesRestApiBusinessFactory;
    }
}
