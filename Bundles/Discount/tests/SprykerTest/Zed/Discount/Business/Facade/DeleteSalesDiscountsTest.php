<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\SalesDiscountCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\Discount\DiscountBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Facade
 * @group DeleteSalesDiscountsTest
 * Add your own group annotations below this line
 */
class DeleteSalesDiscountsTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

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
    public function testDeletesSalesDiscountEntitiesBySalesExpenseIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $expenseTransfer = $this->tester->haveSalesExpense(
            [ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail()],
        );
        $discountVoucherTransfer = $this->tester->createDiscountVoucher();
        $this->tester->createSalesDiscountEntities($saveOrderTransfer, $discountVoucherTransfer, $expenseTransfer);
        $salesDiscountEntity = $this->tester->createSalesDiscountEntities($saveOrderTransfer, $discountVoucherTransfer);
        $salesDiscountCollectionDeleteCriteriaTransfer = (new SalesDiscountCollectionDeleteCriteriaTransfer())
            ->addIdSalesExpense($expenseTransfer->getIdSalesExpenseOrFail());

        // Act
        $this->tester->getFacade()->deleteSalesDiscounts($salesDiscountCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertSalesDiscountEntities($salesDiscountEntity->getIdSalesDiscount());
    }

    /**
     * @group test
     *
     * @return void
     */
    public function testDeletesSalesDiscountEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $discountVoucherTransfer = $this->tester->createDiscountVoucher();
        $this->tester->createSalesDiscountEntities(
            $saveOrderTransfer,
            $discountVoucherTransfer,
            null,
            $idSalesOrderItemToDelete,
        );
        $salesDiscountEntity = $this->tester->createSalesDiscountEntities(
            $saveOrderTransfer,
            $discountVoucherTransfer,
            null,
            $salesOrderItemEntity->getIdSalesOrderItem(),
        );
        $salesDiscountCollectionDeleteCriteriaTransfer = (new SalesDiscountCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItemToDelete);

        // Act
        $this->tester->getFacade()->deleteSalesDiscounts($salesDiscountCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertSalesDiscountEntities($salesDiscountEntity->getIdSalesDiscount());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteAnySalesDiscountEntitiesWhenConditionsAreNotProvided(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $expenseTransfer = $this->tester->haveSalesExpense(
            [ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail()],
        );
        $discountVoucherTransfer = $this->tester->createDiscountVoucher();
        $this->tester->createSalesDiscountEntities($saveOrderTransfer, $discountVoucherTransfer, $expenseTransfer);
        $this->tester->createSalesDiscountEntities($saveOrderTransfer, $discountVoucherTransfer);

        // Act
        $this->tester->getFacade()->deleteSalesDiscounts(new SalesDiscountCollectionDeleteCriteriaTransfer());

        // Assert
        $this->assertSame(2, $this->tester->getSalesDiscountEntities()->count());
        $this->assertSame(2, $this->tester->getSalesDiscountCodeEntities()->count());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteAnySalesDiscountEntitiesWhenEntitiesAreNotFoundByProvidedSalesExpenseIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $expenseTransfer = $this->tester->haveSalesExpense(
            [ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail()],
        );
        $discountVoucherTransfer = $this->tester->createDiscountVoucher();
        $salesDiscountEntity = $this->tester->createSalesDiscountEntities($saveOrderTransfer, $discountVoucherTransfer, $expenseTransfer);
        $salesDiscountCollectionDeleteCriteriaTransfer = (new SalesDiscountCollectionDeleteCriteriaTransfer())
            ->addIdSalesExpense(-1);

        // Act
        $this->tester->getFacade()->deleteSalesDiscounts($salesDiscountCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertSalesDiscountEntities($salesDiscountEntity->getIdSalesDiscount());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteAnySalesDiscountEntitiesWhenEntitiesAreNotFoundByProvidedSalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $discountVoucherTransfer = $this->tester->createDiscountVoucher();
        $salesDiscountEntity = $this->tester->createSalesDiscountEntities(
            $saveOrderTransfer,
            $discountVoucherTransfer,
            null,
            $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail(),
        );
        $salesDiscountCollectionDeleteCriteriaTransfer = (new SalesDiscountCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteSalesDiscounts($salesDiscountCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertSalesDiscountEntities($salesDiscountEntity->getIdSalesDiscount());
    }

    /**
     * @param int $idSalesDiscount
     *
     * @return void
     */
    protected function assertSalesDiscountEntities(int $idSalesDiscount): void
    {
        $salesDiscountEntities = $this->tester->getSalesDiscountEntities();
        $salesDiscountCodeEntities = $this->tester->getSalesDiscountCodeEntities();

        $this->assertSame(1, $salesDiscountEntities->count());
        $this->assertSame($idSalesDiscount, $salesDiscountEntities[0]->getIdSalesDiscount());
        $this->assertSame(1, $salesDiscountCodeEntities->count());
        $this->assertSame($idSalesDiscount, $salesDiscountCodeEntities[0]->getFkSalesDiscount());
    }
}
