<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\OmsEventTimeoutCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\Oms\OmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group DeleteOmsEventTimeoutCollectionTest
 * Add your own group annotations below this line
 */
class DeleteOmsEventTimeoutCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected OmsBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureOmsEventTimeoutTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testDeletesOmsEventTimeoutEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem1 = $orderTransfer->getItems()->offsetGet(0)->getIdSalesOrderItem();
        $idSalesOrderItem2 = $orderTransfer->getItems()->offsetGet(1)->getIdSalesOrderItem();
        $omsOrderItemStateEntity = $this->tester->haveOmsOrderItemStateEntity('test-state');
        $dateTime = new DateTime('now');
        $this->tester->haveOmsEventTimeoutEntity([
            'fk_sales_order_item' => $idSalesOrderItem1,
            'fk_oms_order_item_state' => $omsOrderItemStateEntity->getIdOmsOrderItemState(),
            'event' => 'test-event-1',
            'timeout' => $dateTime,
        ]);
        $this->tester->haveOmsEventTimeoutEntity([
            'fk_sales_order_item' => $idSalesOrderItem2,
            'fk_oms_order_item_state' => $omsOrderItemStateEntity->getIdOmsOrderItemState(),
            'event' => 'test-event-2',
            'timeout' => $dateTime,
        ]);
        $omsEventTimeoutCollectionDeleteCriteriaTransfer = (new OmsEventTimeoutCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItem1);

        // Act
        $this->tester->getFacade()->deleteOmsEventTimeoutCollection($omsEventTimeoutCollectionDeleteCriteriaTransfer);

        // Assert
        $omsEventTimeoutEntities = $this->tester->getOmsEventTimeoutEntities();

        $this->assertCount(1, $omsEventTimeoutEntities);
        $this->assertSame($idSalesOrderItem2, $omsEventTimeoutEntities[0]->getFkSalesOrderItem());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteOmsEventTimeoutEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $orderTransfer->getItems()->offsetGet(0)->getIdSalesOrderItem();
        $omsOrderItemStateEntity = $this->tester->haveOmsOrderItemStateEntity('test-state');
        $dateTime = new DateTime('now');
        $this->tester->haveOmsEventTimeoutEntity([
            'fk_sales_order_item' => $idSalesOrderItem,
            'fk_oms_order_item_state' => $omsOrderItemStateEntity->getIdOmsOrderItemState(),
            'event' => 'test-event-1',
            'timeout' => $dateTime,
        ]);
        $omsEventTimeoutCollectionDeleteCriteriaTransfer = (new OmsEventTimeoutCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteOmsEventTimeoutCollection($omsEventTimeoutCollectionDeleteCriteriaTransfer);

        // Assert
        $omsEventTimeoutEntities = $this->tester->getOmsEventTimeoutEntities();

        $this->assertCount(1, $omsEventTimeoutEntities);
        $this->assertSame($idSalesOrderItem, $omsEventTimeoutEntities[0]->getFkSalesOrderItem());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteOmsEventTimeoutEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $orderTransfer->getItems()->offsetGet(0)->getIdSalesOrderItem();
        $omsOrderItemStateEntity = $this->tester->haveOmsOrderItemStateEntity('test-state');
        $dateTime = new DateTime('now');
        $this->tester->haveOmsEventTimeoutEntity([
            'fk_sales_order_item' => $idSalesOrderItem,
            'fk_oms_order_item_state' => $omsOrderItemStateEntity->getIdOmsOrderItemState(),
            'event' => 'test-event-1',
            'timeout' => $dateTime,
        ]);

        // Act
        $this->tester->getFacade()->deleteOmsEventTimeoutCollection(new OmsEventTimeoutCollectionDeleteCriteriaTransfer());

        // Assert
        $omsEventTimeoutEntities = $this->tester->getOmsEventTimeoutEntities();

        $this->assertCount(1, $omsEventTimeoutEntities);
        $this->assertSame($idSalesOrderItem, $omsEventTimeoutEntities[0]->getFkSalesOrderItem());
    }
}
