<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\Oms\OmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group DeleteOmsOrderItemStateHistoryCollectionTest
 * Add your own group annotations below this line
 */
class DeleteOmsOrderItemStateHistoryCollectionTest extends Unit
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
        $this->tester->ensureOmsOrderItemStateHistoryTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testDeletesOmsOrderItemStateHistoryEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem1 = $orderTransfer->getItems()->offsetGet(0)->getIdSalesOrderItem();
        $idSalesOrderItem2 = $orderTransfer->getItems()->offsetGet(1)->getIdSalesOrderItem();
        $omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer = (new OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItem1);

        // Act
        $this->tester->getFacade()->deleteOmsOrderItemStateHistoryCollection($omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer);

        // Assert
        $omsOrderItemStateHistoryEntities = $this->tester->getOmsOrderItemStateHistoryEntities();

        $this->assertCount(1, $omsOrderItemStateHistoryEntities);
        $this->assertSame($idSalesOrderItem2, $omsOrderItemStateHistoryEntities[0]->getFkSalesOrderItem());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteOmsOrderItemStateHistoryEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $orderTransfer->getItems()->offsetGet(0)->getIdSalesOrderItem();
        $omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer = (new OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteOmsOrderItemStateHistoryCollection($omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer);

        // Assert
        $omsOrderItemStateHistoryEntities = $this->tester->getOmsOrderItemStateHistoryEntities();

        $this->assertCount(2, $omsOrderItemStateHistoryEntities);
        $this->assertSame($idSalesOrderItem, $omsOrderItemStateHistoryEntities[0]->getFkSalesOrderItem());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteOmsOrderItemStateHistoryEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $orderTransfer->getItems()->offsetGet(0)->getIdSalesOrderItem();

        // Act
        $this->tester->getFacade()->deleteOmsOrderItemStateHistoryCollection(new OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer());

        // Assert
        $omsOrderItemStateHistoryEntities = $this->tester->getOmsOrderItemStateHistoryEntities();

        $this->assertCount(2, $omsOrderItemStateHistoryEntities);
        $this->assertSame($idSalesOrderItem, $omsOrderItemStateHistoryEntities[0]->getFkSalesOrderItem());
    }
}
