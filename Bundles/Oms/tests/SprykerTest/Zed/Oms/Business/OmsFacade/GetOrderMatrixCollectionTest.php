<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderMatrixConditionsTransfer;
use Generated\Shared\Transfer\OrderMatrixCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsPersistenceFactory;
use Spryker\Zed\Oms\Persistence\OmsRepository;
use SprykerTest\Zed\Oms\OmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group GetOrderMatrixCollectionTest
 * Add your own group annotations below this line
 */
class GetOrderMatrixCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected OmsBusinessTester $tester;

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected OmsFacadeInterface $omsFacade;

    /**
     * @return void
     */
    public function testGetOrderMatrixCollectionWithValidCriteriaShouldReturnNonEmptyArray(): void
    {
        // Arrange
        $testStateMachineProcessName = $this->tester::DEFAULT_OMS_PROCESS_NAME;
        $this->tester->createOrderByStateMachineProcessName($testStateMachineProcessName);
        $orderProcessEntity = SpyOmsOrderProcessQuery::create()->filterByName($testStateMachineProcessName)->findOne();
        $orderMatrixCriteriaTransfer = (new OrderMatrixCriteriaTransfer())
            ->setOrderMatrixConditions((new OrderMatrixConditionsTransfer())->addProcessId($orderProcessEntity->getIdOmsOrderProcess()));

        // Act
        $result = $this->omsFacade->getOrderMatrixCollection($orderMatrixCriteriaTransfer);

        // Assert
        $this->tester->assertCount(1, $result->getOrderMatrices());
    }

    /**
     * @return void
     */
    public function testGetOrderMatrixCollectionShouldReturnEmptyArrayWhenNoItemsMatchCriteria(): void
    {
        // Arrange
        $orderMatrixCriteriaTransfer = (new OrderMatrixCriteriaTransfer())
            ->setPagination((new PaginationTransfer())->setLimit(10))
            ->setOrderMatrixConditions(new OrderMatrixConditionsTransfer());

        // Act
        $result = $this->omsFacade->getOrderMatrixCollection($orderMatrixCriteriaTransfer);

        // Assert
        $this->assertEmpty($result->getOrderMatrices());
    }

    /**
     * @return void
     */
    public function testGetOrderMatrixCollectionShouldCorrectlyApplyBlackListStates(): void
    {
        // Arrange
        $testStateMachineProcessName = $this->tester::DEFAULT_OMS_PROCESS_NAME;
        $firstOrder = $this->tester->createOrderByStateMachineProcessName($testStateMachineProcessName);
        $this->tester->createOrderByStateMachineProcessName($testStateMachineProcessName);
        $firstOrderItems = SpySalesOrderItemQuery::create()->filterByFkSalesOrder($firstOrder->getIdSalesOrder())->find();
        $cancelledState = SpyOmsOrderItemStateQuery::create()->filterByName('cancelled')->findOne();
        if (!$cancelledState) {
            $cancelledState = new SpyOmsOrderItemState();
            $cancelledState->setName('cancelled');
            $cancelledState->save();
        }
        foreach ($firstOrderItems as $item) {
            $item->setFkOmsOrderItemState($cancelledState->getIdOmsOrderItemState());
            $item->save();
        }
        $orderProcessEntity = SpyOmsOrderProcessQuery::create()->filterByName($testStateMachineProcessName)->findOne();
        $orderMatrixCriteriaTransfer = new OrderMatrixCriteriaTransfer();
        $orderMatrixConditionsTransfer = (new OrderMatrixConditionsTransfer())
            ->addProcessId($orderProcessEntity->getIdOmsOrderProcess());
        $configMock = $this->createMock(OmsConfig::class);
        $configMock->method('getStateBlacklist')->willReturn([$cancelledState->getName()]);
        $factory = new OmsPersistenceFactory();
        $factory->setConfig($configMock);
        $repository = new OmsRepository();
        $repository->setFactory($factory);
        $omsFacade = new OmsFacade();
        $omsFacade->setRepository($repository);
        $orderMatrixCriteriaTransfer->setOrderMatrixConditions($orderMatrixConditionsTransfer);
        // Act
        $result = $omsFacade->getOrderMatrixCollection($orderMatrixCriteriaTransfer);

        // Assert
        $this->tester->assertCount(1, $result->getOrderMatrices());
    }

    /**
     * @return void
     */
    public function testGetProcessNamesIndexedByIdOmsOrderProcessShouldReturnArrayOfProcessesWithExpectedAmount(): void
    {
        // Arrange
        $testStateMachineProcessName = $this->tester::DEFAULT_OMS_PROCESS_NAME;
        $this->tester->createOrderByStateMachineProcessName($testStateMachineProcessName);

        // Act
        $result = $this->omsFacade->getProcessNamesIndexedByIdOmsOrderProcess();

        // Assert
        $this->tester->assertIsArray($result);
        $this->tester->assertContains($testStateMachineProcessName, $result);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->omsFacade = $this->tester->getFacade();
        $this->tester->resetReservedStatesCache();
        $this->tester->configureTestStateMachine(['Test01']);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tester->resetReservedStatesCache();
    }
}
