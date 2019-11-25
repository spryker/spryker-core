<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartCode\Business;

use Codeception\Test\Unit;
use Spryker\Zed\CartCode\CartCodeDependencyProvider;
use Spryker\Zed\Discount\Communication\Plugin\CartCode\VoucherCartCodePlugin;

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

        $this->setPluginCartCodeCollection();
    }

    /**
     * @return void
     */
    public function testAddCandidateAddsVoucherCodeToUnlockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer(false);

        // Act
        $cartCodeOperationResultTransfer = $this->tester->getFacade()->addCandidate($quoteTransfer, static::CODE);

        // Assert
        $this->assertEquals(1, $cartCodeOperationResultTransfer->getQuote()->getVoucherDiscounts()->count());
        $this->assertEquals(
            static::CODE,
            $cartCodeOperationResultTransfer->getQuote()->getVoucherDiscounts()[0]->getVoucherCode()
        );
    }

    /**
     * @return void
     */
    public function testAddCandidateDoesNotAddVoucherCodeToLockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer(true);

        // Act
        $cartCodeOperationResultTransfer = $this->tester->getFacade()->addCandidate($quoteTransfer, static::CODE);

        // Assert
        $this->assertEquals(0, $cartCodeOperationResultTransfer->getQuote()->getVoucherDiscounts()->count());
    }

    /**
     * @return void
     */
    public function testRemoveCodeWillRemoveDiscountFromCartWithUnlockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferWithDiscount(false, static::CODE);

        // Act
        $cartCodeOperationResultTransfer = $this->tester->getFacade()->removeCartCode($quoteTransfer, static::CODE);

        // Assert
        $this->assertEquals(0, $cartCodeOperationResultTransfer->getQuote()->getVoucherDiscounts()->count());
    }

    /**
     * @return void
     */
    public function testRemoveCodeWillNotRemoveDiscountFromCartWithLockedQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferWithDiscount(true, static::CODE);

        // Act
        $cartCodeOperationResultTransfer = $this->tester->getFacade()->removeCartCode($quoteTransfer, static::CODE);

        // Assert
        $this->assertEquals(1, $cartCodeOperationResultTransfer->getQuote()->getVoucherDiscounts()->count());
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
}
