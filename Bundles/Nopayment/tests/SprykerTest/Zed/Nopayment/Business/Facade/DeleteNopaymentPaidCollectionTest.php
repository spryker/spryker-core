<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Nopayment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\NopaymentPaidCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\Nopayment\NopaymentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Nopayment
 * @group Business
 * @group Facade
 * @group DeleteNopaymentPaidCollectionTest
 * Add your own group annotations below this line
 */
class DeleteNopaymentPaidCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\Nopayment\NopaymentBusinessTester
     */
    protected NopaymentBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureNopaymentPaidTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testDeletesNopaymentPaidEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $this->tester->createNopaymentPaidEntity($idSalesOrderItemToDelete);
        $nopaymentPaidEntity = $this->tester->createNopaymentPaidEntity($salesOrderItemEntity->getIdSalesOrderItem());
        $nopaymentPaidCollectionDeleteCriteriaTransfer = (new NopaymentPaidCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItemToDelete);

        // Act
        $this->tester->getFacade()->deleteNopaymentPaidCollection($nopaymentPaidCollectionDeleteCriteriaTransfer);

        // Assert
        $nopaymentPaidEntities = $this->tester->getNopaymentPaidEntities();

        $this->tester->assertCount(1, $nopaymentPaidEntities);
        $this->assertSame($nopaymentPaidEntity->getIdNopaymentPaid(), $nopaymentPaidEntities[0]->getIdNopaymentPaid());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteNopaymentPaidEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $nopaymentPaidEntity = $this->tester->createNopaymentPaidEntity($idSalesOrderItem);
        $nopaymentPaidCollectionDeleteCriteriaTransfer = (new NopaymentPaidCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteNopaymentPaidCollection($nopaymentPaidCollectionDeleteCriteriaTransfer);

        // Assert
        $nopaymentPaidEntities = $this->tester->getNopaymentPaidEntities();

        $this->assertCount(1, $nopaymentPaidEntities);
        $this->assertSame($nopaymentPaidEntity->getIdNopaymentPaid(), $nopaymentPaidEntities[0]->getIdNopaymentPaid());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteNopaymentPaidEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $nopaymentPaidEntity = $this->tester->createNopaymentPaidEntity($idSalesOrderItem);

        // Act
        $this->tester->getFacade()->deleteNopaymentPaidCollection(new NopaymentPaidCollectionDeleteCriteriaTransfer());

        // Assert
        $nopaymentPaidEntities = $this->tester->getNopaymentPaidEntities();

        $this->assertCount(1, $nopaymentPaidEntities);
        $this->assertSame($nopaymentPaidEntity->getIdNopaymentPaid(), $nopaymentPaidEntities[0]->getIdNopaymentPaid());
    }
}
