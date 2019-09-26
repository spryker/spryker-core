<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Session\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use ReflectionClass;
use SessionHandlerInterface;
use Silex\Application;
use Spryker\Client\Session\SessionClient;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\Session\Business\Handler\SessionHandlerFile;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedis;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking;
use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface;
use Spryker\Shared\Session\SessionConfig;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider;
use Spryker\Zed\Session\Communication\SessionCommunicationFactory;
use Spryker\Zed\Session\SessionConfig as ZedSessionConfig;
use Spryker\Zed\Session\SessionDependencyProvider;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Session
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group SessionServiceProviderTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\Session\SessionCommunicationTester $tester
 */
class SessionServiceProviderTest extends Unit
{
    use ConfigHelperTrait;

    protected const DUMMY_SESSION_HANDLER_NAME = 'DUMMY_SESSION_HANDLER_NAME';

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
        $sessionServiceProvider = $this->createSessionServiceProviderWithFactoryMock();

        $sessionServiceProvider->register($application);

        $this->assertArrayHasKey('session.storage.options', $application);
        $this->assertIsArray($application['session.storage.options']);
    }

    /**
     * @return void
     */
    public function testRegisterShouldSetSessionStorageHandler()
    {
        $application = new Application();
        $sessionServiceProvider = $this->createSessionServiceProviderWithFactoryMock();

        $sessionServiceProvider->register($application);

        $this->assertArrayHasKey('session.storage.handler', $application);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Session\SessionClientInterface
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
     * @deprecated Will be removed with next major release.
     *
     * @return void
     */
    public function testCanBeUsedWithSessionHandlerRedis()
    {
        $this->setConfig(SessionConstants::ZED_SESSION_SAVE_HANDLER, SessionConfig::SESSION_HANDLER_REDIS);

        $application = new Application();
        $sessionServiceProvider = $this->createSessionServiceProviderWithFactoryMock();

        $sessionServiceProvider->register($application);

        $this->assertInstanceOf(SessionHandlerRedis::class, $application['session.storage.handler']);
    }

    /**
     * @deprecated Will be removed with next major release.
     *
     * @return void
     */
    public function testCanBeUsedWithSessionHandlerRedisLock()
    {
        $this->setConfig(SessionConstants::ZED_SESSION_SAVE_HANDLER, SessionConfig::SESSION_HANDLER_REDIS_LOCKING);

        $application = new Application();
        $sessionServiceProvider = $this->createSessionServiceProviderWithFactoryMock();

        $sessionServiceProvider->register($application);

        $this->assertInstanceOf(SessionHandlerRedisLocking::class, $application['session.storage.handler']);
    }

    /**
     * @deprecated Will be removed with next major release.
     *
     * @return void
     */
    public function testCanBeUsedWithSessionHandlerFile()
    {
        $this->setConfig(SessionConstants::ZED_SESSION_SAVE_HANDLER, SessionConfig::SESSION_HANDLER_FILE);

        $application = new Application();
        $sessionServiceProvider = $this->createSessionServiceProviderWithFactoryMock();

        $sessionServiceProvider->register($application);

        $this->assertInstanceOf(SessionHandlerFile::class, $application['session.storage.handler']);
    }

    /**
     * @return void
     */
    public function testCanBeUsedWithSessionHandlerProviderPlugin(): void
    {
        // Arrange
        $this->setupSessionHandlerPluginDependency();
        $this->setConfig(SessionConstants::ZED_SESSION_SAVE_HANDLER, static::DUMMY_SESSION_HANDLER_NAME);
        $application = new Application();
        $sessionServiceProvider = new SessionServiceProvider();

        // Act
        $sessionServiceProvider->register($application);

        // Assert
        $this->assertInstanceOf(SessionHandlerInterface::class, $application['session.storage.handler']);
    }

    /**
     * @return void
     */
    protected function setupSessionHandlerPluginDependency(): void
    {
        $sessionHandlerProviderPluginMock = $this->createMock(SessionHandlerProviderPluginInterface::class);
        $sessionHandlerProviderPluginMock->method('getSessionHandlerName')->willReturn(static::DUMMY_SESSION_HANDLER_NAME);
        $sessionHandlerProviderPluginMock->method('getSessionHandler')->willReturn(
            $this->createMock(SessionHandlerInterface::class)
        );

        $this->tester->setDependency(SessionDependencyProvider::PLUGINS_SESSION_HANDLER, function (Container $container) use ($sessionHandlerProviderPluginMock) {
            return [
                $sessionHandlerProviderPluginMock,
            ];
        });
    }

    /**
     * @return \Spryker\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider
     */
    protected function createSessionServiceProviderWithFactoryMock(): SessionServiceProvider
    {
        $sessionServiceProvider = new SessionServiceProvider();
        $sessionServiceProviderReflection = new ReflectionClass($sessionServiceProvider);
        $factoryProperty = $sessionServiceProviderReflection->getParentClass()->getProperty('factory');
        $factoryProperty->setAccessible(true);
        $sessionFactoryMock = $this->createSessionCommunicationFactoryMock();

        $factoryProperty->setValue($sessionServiceProvider, $sessionFactoryMock);

        return $sessionServiceProvider;
    }

    /**
     * @return \Spryker\Zed\Session\Communication\SessionCommunicationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSessionCommunicationFactoryMock()
    {
        $sessionFactoryMock = $this->getMockBuilder(SessionCommunicationFactory::class)
            ->setMethods([
                'createSessionHandlerRedis',
                'createSessionHandlerRedisLocking',
                'createSessionHandlerFile',
                'getSessionHandlerPlugins',
                'getMonitoringService',
                'getSessionClient',
                'getConfig',
            ])
            ->getMock();
        $sessionFactoryMock->method('createSessionHandlerRedis')->willReturn(
            $this->createMock(SessionHandlerRedis::class)
        );
        $sessionFactoryMock->method('createSessionHandlerRedisLocking')->willReturn(
            $this->createMock(SessionHandlerRedisLocking::class)
        );
        $sessionFactoryMock->method('createSessionHandlerFile')->willReturn(
            $this->createMock(SessionHandlerFile::class)
        );
        $sessionFactoryMock->method('getSessionHandlerPlugins')->willReturn([]);
        $sessionFactoryMock->method('getMonitoringService')->willReturn(
            $this->createMock(SessionToMonitoringServiceInterface::class)
        );
        $sessionFactoryMock->method('getConfig')->willReturn(
            new ZedSessionConfig()
        );
        $sessionFactoryMock->method('getSessionClient')->willReturn(
            $this->createMock(SessionClientInterface::class)
        );

        return $sessionFactoryMock;
    }
}
