<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartCodesRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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

    protected const QUOTE_UUID = 'QUOTE_UUID';

    protected const ID_DISCOUNT = 9999;

    protected const DISCOUNT_PERCENTAGE_200 = 20000;

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
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::UUID => uniqid('uuid', true),
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

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
    public function testAddCandidateWillNotAddCodeWithNonExistentQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $cartCodeOperationResultTransfer = $this->tester->getFacade()->addCandidate($quoteTransfer, static::CODE);

        // Assert
        $this->assertEquals(
            static::ERROR_IDENTIFIER_CART_NOT_FOUND,
            $cartCodeOperationResultTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testRemoveCodeWillRemoveDiscountWithExistingQuote(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $discountVoucherTransfer = $this->tester->prepareDiscountVoucherTransfer();

        $discountTransfer = (new DiscountTransfer())
            ->setVoucherCode($discountVoucherTransfer->getCode())
            ->setIdDiscount($discountVoucherTransfer->getIdDiscount());

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::UUID => uniqid('uuid'),
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::VOUCHER_DISCOUNTS => [$discountTransfer->toArray()],
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::QUANTITY => 3,
                    ItemTransfer::SKU => 123,
                ],
            ],
            QuoteTransfer::STORE => [
                StoreTransfer::NAME => 'DE',
                StoreTransfer::ID_STORE => 1,
            ],
        ]);

        // Act
        $cartCodeOperationResultTransfer = $this->tester->getFacade()->removeCode($quoteTransfer, $discountVoucherTransfer->getIdDiscount());

        // Assert
        $this->assertEmpty($cartCodeOperationResultTransfer->getQuote()->getVoucherDiscounts());
    }

    /**
     * @return void
     */
    public function testRemoveCodeWillNotRemoveDiscountWithNonExistentQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $cartCodeOperationResultTransfer = $this->tester->getFacade()->removeCode($quoteTransfer, static::ID_DISCOUNT);

        // Assert
        $this->assertEquals(
            static::ERROR_IDENTIFIER_CART_NOT_FOUND,
            $cartCodeOperationResultTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    protected function setPluginCartCodeCollection(): void
    {
        $this->tester->setDependency(CartCodeDependencyProvider::PLUGIN_CART_CODE_COLLECTION, [
            new VoucherCartCodePlugin(),
        ]);
    }
}
