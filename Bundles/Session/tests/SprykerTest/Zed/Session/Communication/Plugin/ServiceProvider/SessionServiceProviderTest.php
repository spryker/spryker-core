<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Session\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Client\Session\SessionClient;
use Spryker\Shared\Session\Business\Handler\SessionHandlerFile;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedis;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Session
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group SessionServiceProviderTest
 * Add your own group annotations below this line
 */
class SessionServiceProviderTest extends Unit
{
    use ConfigHelperTrait;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        session_write_close();
    }

    /**
     * @return void
     */
    public function testRegisterShouldSetSessionStorageOptions()
    {
        $application = new Application();
        $sessionServiceProvider = new SessionServiceProvider();

        $sessionServiceProvider->register($application);

        $this->assertArrayHasKey('session.storage.options', $application);
        $this->assertInternalType('array', $application['session.storage.options']);
    }

    /**
     * @return void
     */
    public function testRegisterShouldSetSessionStorageHandler()
    {
        $application = new Application();
        $sessionServiceProvider = new SessionServiceProvider();

        $sessionServiceProvider->register($application);

        $this->assertArrayHasKey('session.storage.handler', $application);
    }

    /**
     * @return void
     */
    public function testBootShouldAddSessionToSessionClientWhenNotInCliOrPhpDbg()
    {
        $sessionClientMock = $this->getSessionClientMock();

        $application = new Application(['session' => $this->getMockBuilder(SessionInterface::class)->getMock()]);

        $sessionServiceProviderMock = $this->getSessionServiceProviderMock();
        $sessionServiceProviderMock->setClient($sessionClientMock);

        $sessionServiceProviderMock->boot($application);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider
     */
    protected function getSessionServiceProviderMock()
    {
        $sessionServiceProviderMockBuilder = $this->getMockBuilder(SessionServiceProvider::class);
        $sessionServiceProviderMockBuilder->setMethods(['isCliOrPhpDbg']);

        $sessionServiceProviderMock = $sessionServiceProviderMockBuilder->getMock();
        $sessionServiceProviderMock->expects($this->once())->method('isCliOrPhpDbg')->willReturn(false);

        return $sessionServiceProviderMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClientMock()
    {
        $sessionClientMockBuilder = $this->getMockBuilder(SessionClient::class);
        $sessionClientMockBuilder->setMethods(['setContainer']);

        $sessionClientMock = $sessionClientMockBuilder->getMock();
        $sessionClientMock->expects($this->once())->method('setContainer');

        return $sessionClientMock;
    }

    /**
     * @return void
     */
    public function testCanBeUsedWithSessionHandlerRedis()
    {
        $this->setConfig(SessionConstants::ZED_SESSION_SAVE_HANDLER, SessionConstants::SESSION_HANDLER_REDIS);

        $application = new Application();
        $sessionServiceProvider = new SessionServiceProvider();

        $sessionServiceProvider->register($application);

        $this->assertInstanceOf(SessionHandlerRedis::class, $application['session.storage.handler']);
    }

    /**
     * @return void
     */
    public function testCanBeUsedWithSessionHandlerRedisLock()
    {
        $this->setConfig(SessionConstants::ZED_SESSION_SAVE_HANDLER, SessionConstants::SESSION_HANDLER_REDIS_LOCKING);

        $application = new Application();
        $sessionServiceProvider = new SessionServiceProvider();

        $sessionServiceProvider->register($application);

        $this->assertInstanceOf(SessionHandlerRedisLocking::class, $application['session.storage.handler']);
    }

    /**
     * @return void
     */
    public function testCanBeUsedWithSessionHandlerFile()
    {
        $this->setConfig(SessionConstants::ZED_SESSION_SAVE_HANDLER, SessionConstants::SESSION_HANDLER_FILE);

        $application = new Application();
        $sessionServiceProvider = new SessionServiceProvider();

        $sessionServiceProvider->register($application);

        $this->assertInstanceOf(SessionHandlerFile::class, $application['session.storage.handler']);
    }
}
