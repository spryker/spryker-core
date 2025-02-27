<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\GiftCard\GiftCardBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Business
 * @group Facade
 * @group DeleteSalesOrderItemGiftCardCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesOrderItemGiftCardCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\GiftCard\GiftCardBusinessTester
     */
    protected GiftCardBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemGiftCardTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testDeletesSalesOrderItemGiftCardEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $this->tester->createSalesOrderItemGiftCard($idSalesOrderItemToDelete);
        $salesOrderItemGiftCardEntity = $this->tester->createSalesOrderItemGiftCard($salesOrderItemEntity->getIdSalesOrderItem());
        $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer = (new SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItemToDelete);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemGiftCardCollection($salesOrderItemGiftCardCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemGiftCardEntities = $this->tester->getSalesOrderItemGiftCardEntities();

        $this->assertCount(1, $salesOrderItemGiftCardEntities);
        $this->assertSame($salesOrderItemGiftCardEntity->getIdSalesOrderItemGiftCard(), $salesOrderItemGiftCardEntities[0]->getIdSalesOrderItemGiftCard());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemGiftCardEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemGiftCardEntity = $this->tester->createSalesOrderItemGiftCard($idSalesOrderItem);
        $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer = (new SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemGiftCardCollection($salesOrderItemGiftCardCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemGiftCardEntities = $this->tester->getSalesOrderItemGiftCardEntities();
        $this->assertCount(1, $salesOrderItemGiftCardEntities);
        $this->assertSame($salesOrderItemGiftCardEntity->getIdSalesOrderItemGiftCard(), $salesOrderItemGiftCardEntities[0]->getIdSalesOrderItemGiftCard());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemGiftCardEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemGiftCardEntity = $this->tester->createSalesOrderItemGiftCard($idSalesOrderItem);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemGiftCardCollection(new SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer());

        // Assert
        $salesOrderItemGiftCardEntities = $this->tester->getSalesOrderItemGiftCardEntities();
        $this->assertCount(1, $salesOrderItemGiftCardEntities);
        $this->assertSame($salesOrderItemGiftCardEntity->getIdSalesOrderItemGiftCard(), $salesOrderItemGiftCardEntities[0]->getIdSalesOrderItemGiftCard());
    }
}
