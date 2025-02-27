<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsTransitionLogCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\Oms\OmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group DeleteOmsTransitionLogCollectionTest
 * Add your own group annotations below this line
 */
class DeleteOmsTransitionLogCollectionTest extends Unit
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
        $this->tester->ensureOmsTransitionLogTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testDeletesOmsTransitionLogEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem1 = $orderTransfer->getItems()->offsetGet(0)->getIdSalesOrderItem();
        $idSalesOrderItem2 = $orderTransfer->getItems()->offsetGet(1)->getIdSalesOrderItem();
        $this->tester->createOmsTransitionLog($orderTransfer->getIdSalesOrderOrFail(), $idSalesOrderItem1);
        $omsTransitionLogEntity2 = $this->tester->createOmsTransitionLog($orderTransfer->getIdSalesOrderOrFail(), $idSalesOrderItem2);
        $omsTransitionLogCollectionDeleteCriteriaTransfer = (new OmsTransitionLogCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItem1);

        // Act
        $this->tester->getFacade()->deleteOmsTransitionLogCollection($omsTransitionLogCollectionDeleteCriteriaTransfer);

        // Assert
        $omsTransitionLogEntities = $this->tester->getOmsTransitionLogEntities();

        $this->assertCount(1, $omsTransitionLogEntities);
        $this->assertSame($omsTransitionLogEntity2->getIdOmsTransitionLog(), $omsTransitionLogEntities[0]->getIdOmsTransitionLog());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteOmsTransitionLogEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $orderTransfer->getItems()->offsetGet(1)->getIdSalesOrderItem();
        $omsTransitionLogEntity = $this->tester->createOmsTransitionLog($orderTransfer->getIdSalesOrderOrFail(), $idSalesOrderItem);
        $omsTransitionLogCollectionDeleteCriteriaTransfer = (new OmsTransitionLogCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteOmsTransitionLogCollection($omsTransitionLogCollectionDeleteCriteriaTransfer);

        // Assert
        $omsTransitionLogEntities = $this->tester->getOmsTransitionLogEntities();

        $this->assertCount(1, $omsTransitionLogEntities);
        $this->assertSame($omsTransitionLogEntity->getIdOmsTransitionLog(), $omsTransitionLogEntities[0]->getIdOmsTransitionLog());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteOmsTransitionLogEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $orderTransfer->getItems()->offsetGet(1)->getIdSalesOrderItem();
        $omsTransitionLogEntity = $this->tester->createOmsTransitionLog($orderTransfer->getIdSalesOrderOrFail(), $idSalesOrderItem);

        // Act
        $this->tester->getFacade()->deleteOmsTransitionLogCollection(new OmsTransitionLogCollectionDeleteCriteriaTransfer());

        // Assert
        $omsTransitionLogEntities = $this->tester->getOmsTransitionLogEntities();

        $this->assertCount(1, $omsTransitionLogEntities);
        $this->assertSame($omsTransitionLogEntity->getIdOmsTransitionLog(), $omsTransitionLogEntities[0]->getIdOmsTransitionLog());
    }
}
