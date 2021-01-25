<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\OrderStateMachine\Builder;
use Spryker\Zed\Oms\Business\OrderStateMachine\Finder;
use Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface;
use Spryker\Zed\Oms\Business\Process\Event;
use Spryker\Zed\Oms\Business\Process\Process;
use Spryker\Zed\Oms\Business\Process\State;
use Spryker\Zed\Oms\Business\Process\Transition;
use Spryker\Zed\Oms\Business\Util\DrawerInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OrderStateMachine
 * @group FinderTest
 * Add your own group annotations below this line
 */
class FinderTest extends Unit
{
    public const STATE_DISPLAY_VALUE = 'name display value';
    public const STATE_SUB_PROCESS_DISPLAY_VALUE = 'sub process state display value';
    public const TEST_STATE_MACHINE_NAME = 'StateMachine01';

    /**
     * @return void
     */
    public function testGetStateDisplayNameShouldReturnDisplayName(): void
    {
        $finder = $this->createFinder();

        $salesOrderItemEntity = $this->createSalesOrderItemEntity();

        $displayName = $finder->getStateDisplayName($salesOrderItemEntity);

        $this->assertSame(static::STATE_DISPLAY_VALUE, $displayName);
    }

    /**
     * @return void
     */
    public function testGetStateDisplayNameWhenSubProcessRequestedShouldReturnDisplayName(): void
    {
        $finder = $this->createFinder();

        $salesOrderItemEntity = $this->createSalesOrderItemEntity();
        $salesOrderItemEntity->getState()->setName('returned');

        $displayName = $finder->getStateDisplayName($salesOrderItemEntity);

        $this->assertSame(static::STATE_SUB_PROCESS_DISPLAY_VALUE, $displayName);
    }

    /**
     * @return void
     */
    public function testGetStateDisplayNameShouldThrowExceptionWhenStateNotFound(): void
    {
        $this->expectException('Spryker\Zed\Oms\Business\Exception\StateNotFoundException');
        $this->expectExceptionMessage('State with name "not existing" not found in any StateMachine processes.');
        $finder = $this->createFinder();

        $salesOrderItemEntity = $this->createSalesOrderItemEntity();

        $salesOrderItemEntity->getState()->setName('not existing');

        $finder->getStateDisplayName($salesOrderItemEntity);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface
     */
    protected function createFinder(): FinderInterface
    {
        $omsQueryContainerMock = $this->createOmsQueryContainer();

        $drawerMock = $this->createDrawerMock();
        $builder = $this->createBuilder($drawerMock);

        return new Finder(
            $omsQueryContainerMock,
            $builder,
            [
                self::TEST_STATE_MACHINE_NAME,
            ]
        );
    }

    /**
     * @return string
     */
    private function getProcessLocation(): string
    {
        return __DIR__ . '/Finder/Fixtures';
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected function createOmsQueryContainer(): OmsQueryContainerInterface
    {
        return $this->getMockBuilder(OmsQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\Util\DrawerInterface
     */
    protected function createDrawerMock(): DrawerInterface
    {
        return $this->getMockBuilder(DrawerInterface::class)->getMock();
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Util\DrawerInterface $drawerMock
     *
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\Builder
     */
    protected function createBuilder(DrawerInterface $drawerMock): Builder
    {
        return new Builder(
            new Event(),
            new State(),
            new Transition(),
            new Process($drawerMock),
            $this->getProcessLocation()
        );
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItemEntity(): SpySalesOrderItem
    {
        $salesOrderItemEntity = new SpySalesOrderItem();

        $omsOrderProcessEntity = new SpyOmsOrderProcess();
        $omsOrderProcessEntity->setName(static::TEST_STATE_MACHINE_NAME);
        $salesOrderItemEntity->setProcess($omsOrderProcessEntity);

        $omsOrderItemStateEntity = new SpyOmsOrderItemState();
        $omsOrderItemStateEntity->setName('new');
        $salesOrderItemEntity->setState($omsOrderItemStateEntity);

        return $salesOrderItemEntity;
    }
}
