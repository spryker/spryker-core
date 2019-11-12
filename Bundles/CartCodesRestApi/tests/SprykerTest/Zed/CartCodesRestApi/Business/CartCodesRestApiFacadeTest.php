<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartCodesRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartCodesRestApi\CartCodesRestApiConfig;
use Spryker\Shared\Quote\QuoteConstants;
use Spryker\Zed\CartCode\CartCodeDependencyProvider;
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
class CartCodesRestApiFacadeTest extends Unit
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
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setPluginCartCodeCollection();
        $this->tester->setConfig(QuoteConstants::FIELDS_ALLOWED_FOR_SAVING, [
            QuoteTransfer::VOUCHER_DISCOUNTS,
        ]);
    }

    /**
     * @return void
     */
    public function testAddCandidateWillAddCodeWithExistingQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->havePersistentQuoteWithOutVouchers();

        // Act
        $cartCodeOperationResultTransfer = $this->tester->getFacade()->addCandidate($quoteTransfer, $this->tester::CODE);

        // Assert
        $this->assertEquals(1, $cartCodeOperationResultTransfer->getQuote()->getVoucherDiscounts()->count());
        $this->assertEquals(
            $this->tester::CODE,
            $cartCodeOperationResultTransfer->getQuote()->getVoucherDiscounts()[0]->getVoucherCode()
        );
    }

    /**
     * @return void
     */
    public function testAddCandidateWillNotAddCodeWithNonExistentQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $cartCodeOperationResultTransfer = $this->tester->getFacade()->addCandidate($quoteTransfer, $this->tester::CODE);

        // Assert
        $this->assertEquals(
            CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND,
            $cartCodeOperationResultTransfer->getMessages()[0]->getValue()
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
}
