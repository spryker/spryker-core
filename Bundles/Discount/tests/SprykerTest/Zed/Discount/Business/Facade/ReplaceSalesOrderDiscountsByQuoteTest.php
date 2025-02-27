<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\Discount\DiscountBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Facade
 * @group ReplaceSalesOrderDiscountsByQuoteTest
 * Add your own group annotations below this line
 */
class ReplaceSalesOrderDiscountsByQuoteTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const FIELD_NAME_FK_SALES_ORDER = 'fk_sales_order';

    /**
     * @var string
     */
    protected const FIELD_NAME_NAME = 'name';

    /**
     * @var string
     */
    protected const DISCOUNT_DISPLAY_NAME = 'discount';

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT = 100;

    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected DiscountBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesDiscountCodeTableIsEmpty();
        $this->tester->ensureSalesDiscountTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenIdSalesOrderIsNotSetInSaveOrderTransfer(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idSalesOrder" of transfer `Generated\Shared\Transfer\SaveOrderTransfer` is null.');

        // Act
        $this->tester->getFacade()->replaceSalesOrderDiscountsByQuote(new QuoteTransfer(), new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testDeletesSalesDiscountRelatedEntities(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $discountVoucherTransfer = $this->tester->createDiscountVoucher([
            DiscountVoucherTransfer::NUMBER_OF_USES => 0,
            DiscountVoucherTransfer::MAX_NUMBER_OF_USES => 0,
        ]);
        $salesDiscountEntity = $this->tester->haveSalesDiscount([
            static::FIELD_NAME_FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            static::FIELD_NAME_NAME => 'test discount',
        ]);
        $this->tester->haveSalesDiscountCode(
            $salesDiscountEntity->getIdSalesDiscount(),
            $discountVoucherTransfer->getCodeOrFail(),
            '',
        );

        // Act
        $this->tester->getFacade()->replaceSalesOrderDiscountsByQuote(new QuoteTransfer(), $saveOrderTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getSalesDiscountEntities()->count());
        $this->assertSame(0, $this->tester->getSalesDiscountCodeEntities()->count());
    }

    /**
     * @return void
     */
    public function testAddsSalesDiscountRelatedEntities(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $discountVoucherTransfer = $this->tester->createDiscountVoucher([
            DiscountVoucherTransfer::NUMBER_OF_USES => 0,
            DiscountVoucherTransfer::MAX_NUMBER_OF_USES => 0,
        ]);
        $calculatedDiscountTransfer = (new CalculatedDiscountTransfer())
            ->setDisplayName(static::DISCOUNT_DISPLAY_NAME)
            ->setSumAmount(static::DISCOUNT_AMOUNT)
            ->setVoucherCode($discountVoucherTransfer->getCodeOrFail());
        $saveOrderTransfer->getOrderItems()->offsetGet(0)->addCalculatedDiscount($calculatedDiscountTransfer);

        // Act
        $this->tester->getFacade()->replaceSalesOrderDiscountsByQuote(new QuoteTransfer(), $saveOrderTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getSalesDiscountEntities()->count());
        $this->assertSame(1, $this->tester->getSalesDiscountCodeEntities()->count());
    }

    /**
     * @return void
     */
    public function testReplacesSalesDiscountRelatedEntities(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $discountVoucherTransfer1 = $this->tester->createDiscountVoucher([
            DiscountVoucherTransfer::NUMBER_OF_USES => 0,
            DiscountVoucherTransfer::MAX_NUMBER_OF_USES => 0,
        ]);
        $salesDiscountEntity = $this->tester->haveSalesDiscount([
            static::FIELD_NAME_FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            static::FIELD_NAME_NAME => 'test discount',
        ]);
        $this->tester->haveSalesDiscountCode(
            $salesDiscountEntity->getIdSalesDiscount(),
            $discountVoucherTransfer1->getCodeOrFail(),
            '',
        );
        $discountVoucherTransfer2 = $this->tester->createDiscountVoucher([
            DiscountVoucherTransfer::NUMBER_OF_USES => 0,
            DiscountVoucherTransfer::MAX_NUMBER_OF_USES => 0,
        ]);
        $calculatedDiscountTransfer = (new CalculatedDiscountTransfer())
            ->setDisplayName(static::DISCOUNT_DISPLAY_NAME)
            ->setSumAmount(static::DISCOUNT_AMOUNT)
            ->setVoucherCode($discountVoucherTransfer2->getCodeOrFail());
        $saveOrderTransfer->getOrderItems()->offsetGet(0)->addCalculatedDiscount($calculatedDiscountTransfer);

        // Act
        $this->tester->getFacade()->replaceSalesOrderDiscountsByQuote(new QuoteTransfer(), $saveOrderTransfer);

        // Assert
        $salesDiscountEntities = $this->tester->getSalesDiscountEntities();
        $salesDiscountCodeEntities = $this->tester->getSalesDiscountCodeEntities();

        $this->assertSame(1, $salesDiscountEntities->count());
        $this->assertSame(static::DISCOUNT_DISPLAY_NAME, $salesDiscountEntities[0]->getDisplayName());
        $this->assertSame(1, $salesDiscountCodeEntities->count());
        $this->assertSame($discountVoucherTransfer2->getCode(), $salesDiscountCodeEntities[0]->getCode());
    }
}
