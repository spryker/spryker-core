<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use Spryker\Zed\Oms\Business\Exception\ProcessNotActiveException;
use Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManager;
use Spryker\Zed\Oms\OmsConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OrderStateMachine
 * @group PersistenceManagerTest
 * Add your own group annotations below this line
 */
class PersistenceManagerTest extends Unit
{
    /**
     * @return void
     */
    public function testGetProcessEntityShouldThrowExceptionIfProcessNotSet()
    {
        $omsConfigMock = $this->createOmsConfigMock();

        $omsConfigMock->method('getActiveProcesses')
            ->willReturn([]);

        $persistenceManager = $this->createPersistenceManager($omsConfigMock);

        $this->expectException(ProcessNotActiveException::class);

        $persistenceManager->getProcessEntity('does not exist');
    }

    /**
     * @param \Spryker\Zed\Oms\OmsConfig $omsConfigMock
     *
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManager
     */
    protected function createPersistenceManager(OmsConfig $omsConfigMock)
    {
        return new PersistenceManager($omsConfigMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Oms\OmsConfig
     */
    protected function createOmsConfigMock()
    {
        return $this->getMockBuilder(OmsConfig::class)->getMock();
    }
}
