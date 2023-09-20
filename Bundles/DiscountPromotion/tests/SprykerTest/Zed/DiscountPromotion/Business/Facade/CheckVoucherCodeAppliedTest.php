<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\DiscountBuilder;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Business
 * @group Facade
 * @group CheckVoucherCodeAppliedTest
 * Add your own group annotations below this line
 */
class CheckVoucherCodeAppliedTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_FAKE_VOUCHER_CODE = 'voucher code';

    /**
     * @var string
     */
    protected const TEST_FAKE_VOUCHER_CODE_2 = 'voucher code 2';

    /**
     * @uses \Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionVoucherCodeChecker::GLOSSARY_KEY_VOUCHER_NON_APPLICABLE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VOUCHER_NON_APPLICABLE = 'cart.voucher.apply.non_applicable';

    /**
     * @uses \Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionVoucherCodeChecker::GLOSSARY_KEY_VOUCHER_APPLY_SUCCESSFUL
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VOUCHER_APPLY_SUCCESSFUL = 'cart.voucher.apply.successful';

    /**
     * @uses \Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionVoucherCodeChecker::MESSAGE_TYPE_SUCCESS
     *
     * @var string
     */
    protected const MESSAGE_TYPE_SUCCESS = 'success';

    /**
     * @uses \Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionVoucherCodeChecker::MESSAGE_TYPE_ERROR
     *
     * @var string
     */
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testCheckVoucherCodeAppliedShouldReturnSuccessfulResponseIfVoucherIsInUsedNotAppliedVoucherCodes(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setUsedNotAppliedVoucherCodes([
                static::TEST_FAKE_VOUCHER_CODE,
                static::TEST_FAKE_VOUCHER_CODE_2,
            ]);

        // Act
        $discountVoucherCheckResponseTransfer = $this->tester->getFacade()
            ->checkVoucherCodeApplied($quoteTransfer, static::TEST_FAKE_VOUCHER_CODE);

        // Assert
        $this->assertTrue($discountVoucherCheckResponseTransfer->getIsSuccessful());
        $this->assertNotNull($discountVoucherCheckResponseTransfer->getMessage());
        $this->assertSame(
            static::MESSAGE_TYPE_SUCCESS,
            $discountVoucherCheckResponseTransfer->getMessage()->getType(),
        );
        $this->assertSame(
            static::GLOSSARY_KEY_VOUCHER_APPLY_SUCCESSFUL,
            $discountVoucherCheckResponseTransfer->getMessage()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testCheckVoucherCodeAppliedShouldReturnSuccessfulResponseIfVoucherIsInVoucherDiscounts(): void
    {
        // Arrange
        $voucherDiscountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountTransfer::VOUCHER_CODE => static::TEST_FAKE_VOUCHER_CODE,
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addVoucherDiscount($voucherDiscountTransfer);

        // Act
        $discountVoucherCheckResponseTransfer = $this->tester->getFacade()
            ->checkVoucherCodeApplied($quoteTransfer, static::TEST_FAKE_VOUCHER_CODE);

        // Assert
        $this->assertTrue($discountVoucherCheckResponseTransfer->getIsSuccessful());
        $this->assertNotNull($discountVoucherCheckResponseTransfer->getMessage());
        $this->assertSame(
            static::MESSAGE_TYPE_SUCCESS,
            $discountVoucherCheckResponseTransfer->getMessage()->getType(),
        );
        $this->assertSame(
            static::GLOSSARY_KEY_VOUCHER_APPLY_SUCCESSFUL,
            $discountVoucherCheckResponseTransfer->getMessage()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testCheckVoucherCodeAppliedShouldReturnErrorResponseIfVoucherCodeIsNotInQuote(): void
    {
        // Arrange
        $voucherDiscountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountTransfer::VOUCHER_CODE => static::TEST_FAKE_VOUCHER_CODE,
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())
            ->addVoucherDiscount($voucherDiscountTransfer)
            ->addUsedNotAppliedVoucherCode(static::TEST_FAKE_VOUCHER_CODE);

        // Act
        $discountVoucherCheckResponseTransfer = $this->tester->getFacade()
            ->checkVoucherCodeApplied($quoteTransfer, static::TEST_FAKE_VOUCHER_CODE_2);

        // Assert
        $this->assertFalse($discountVoucherCheckResponseTransfer->getIsSuccessful());
        $this->assertNotNull($discountVoucherCheckResponseTransfer->getMessage());
        $this->assertSame(
            static::MESSAGE_TYPE_ERROR,
            $discountVoucherCheckResponseTransfer->getMessage()->getType(),
        );
        $this->assertSame(
            static::GLOSSARY_KEY_VOUCHER_NON_APPLICABLE,
            $discountVoucherCheckResponseTransfer->getMessage()->getValue(),
        );
    }
}
