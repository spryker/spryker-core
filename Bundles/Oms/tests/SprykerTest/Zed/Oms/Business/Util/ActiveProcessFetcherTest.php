<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\Util;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Zed\Oms\Business\OrderStateMachine\Builder;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Business\Process\Event;
use Spryker\Zed\Oms\Business\Process\Process;
use Spryker\Zed\Oms\Business\Process\State;
use Spryker\Zed\Oms\Business\Process\Transition;
use Spryker\Zed\Oms\Business\Util\ActiveProcessFetcher;
use Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface;
use Spryker\Zed\Oms\Business\Util\DrawerInterface;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Util
 * @group ActiveProcessFetcherTest
 * Add your own group annotations below this line
 */
class ActiveProcessFetcherTest extends Unit
{
    protected const TEST_STATE_MACHINE_NAME = 'ActiveStateMachine';
    protected const RESERVED_STATES = [
        'new',
        'payment pending',
    ];

    /**
     * @return void
     */
    public function testGetReservedStatesFromAllActiveProcesses(): void
    {
        $activeProcessFetcher = $this->createActiveProcessFetcher();

        $reservedStates = $activeProcessFetcher->getReservedStatesFromAllActiveProcesses();

        $reservedStateNames = [];
        foreach ($reservedStates as $state) {
            $reservedStateNames[] = $state->getName();
        }
        $expectedStates = static::RESERVED_STATES;
        sort($expectedStates);
        sort($reservedStateNames);

        $this->assertEquals($expectedStates, $reservedStateNames);
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        $reflectionResolver = new ReflectionClass(ActiveProcessFetcher::class);
        $reflectionProperty = $reflectionResolver->getProperty('reservedStatesCache');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface
     */
    protected function createActiveProcessFetcher(): ActiveProcessFetcherInterface
    {
        $drawerMock = $this->createDrawerMock();
        $builder = $this->createBuilder($drawerMock);

        return new ActiveProcessFetcher(
            new ReadOnlyArrayObject([
                self::TEST_STATE_MACHINE_NAME,
            ]),
            $builder
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Oms\Business\Util\DrawerInterface
     */
    protected function createDrawerMock(): DrawerInterface
    {
        return $this->getMockBuilder(DrawerInterface::class)->getMock();
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Util\DrawerInterface $drawerMock
     *
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    protected function createBuilder(DrawerInterface $drawerMock): BuilderInterface
    {
        return new Builder(
            new Event(),
            new State(),
            new Transition(),
            new Process($drawerMock),
            [$this->getProcessLocation()]
        );
    }

    /**
     * @return string
     */
    protected function getProcessLocation()
    {
        return __DIR__ . '/ActiveProcessFetcher/Fixtures';
    }
}
