<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Session\Business\Model\SessionStorage;

use Codeception\Test\Unit;
use SessionHandlerInterface;
use Spryker\Shared\Session\Exception\SessionHandlerNotFoundInSessionHandlerPoolException;
use Spryker\Shared\Session\Model\SessionStorage\SessionStorageHandlerPool;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Session
 * @group Business
 * @group Model
 * @group SessionStorage
 * @group SessionStorageHandlerPoolTest
 * Add your own group annotations below this line
 */
class SessionStorageHandlerPoolTest extends Unit
{
    public const CONFIGURED_HANDLER_NAME = 'handler name';

    /**
     * @return void
     */
    public function testGetHandlerReturnsAddedHandlerWhichMatchesConfiguredHandlerName()
    {
        $sessionHandlerInterfaceMock = $this->getSessionHandlerInterfaceMock();

        $sessionStorageHandlerPool = new SessionStorageHandlerPool();
        $sessionStorageHandlerPool->addHandler($sessionHandlerInterfaceMock, static::CONFIGURED_HANDLER_NAME);

        $this->assertInstanceOf(SessionHandlerInterface::class, $sessionStorageHandlerPool->getHandler(static::CONFIGURED_HANDLER_NAME));
    }

    /**
     * @return void
     */
    public function testGetHandlerThrowsExceptionWhenTryingToGetNotAddedHandler()
    {
        $this->expectException(SessionHandlerNotFoundInSessionHandlerPoolException::class);

        $sessionStorageHandlerPool = new SessionStorageHandlerPool();
        $sessionStorageHandlerPool->getHandler(static::CONFIGURED_HANDLER_NAME);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\SessionHandlerInterface
     */
    protected function getSessionHandlerInterfaceMock()
    {
        return $this->getMockBuilder(SessionHandlerInterface::class)->getMock();
    }
}
