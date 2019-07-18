<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Session\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use ReflectionClass;
use SessionHandlerInterface;
use Silex\Application;
use Spryker\Shared\Session\Business\Handler\SessionHandlerFile;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedis;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking;
use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface;
use Spryker\Shared\Session\SessionConfig;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Session\Plugin\ServiceProvider\SessionServiceProvider;
use Spryker\Yves\Session\SessionConfig as SessionConfigYves;
use Spryker\Yves\Session\SessionDependencyProvider;
use Spryker\Yves\Session\SessionFactory;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Session
 * @group Plugin
 * @group ServiceProvider
 * @group SessionServiceProviderTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Yves\Session\SessionYvesTester $tester
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
     * @return void
     */
    public function testBootShouldAddSessionToSessionClient()
    {
        $applicationMock = $this->getApplicationMock();
        $sessionServiceProvider = new SessionServiceProvider();

        $sessionServiceProvider->boot($applicationMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Silex\Application
     */
    private function getApplicationMock()
    {
        $sessionMock = $this->getMockBuilder(SessionInterface::class)->getMock();
        $dispatcherMock = $this->getMockBuilder(EventDispatcher::class)->getMock();
        $applicationMockBuilder = $this->getMockBuilder(Application::class);
        $applicationMockBuilder->setMethods(['offsetGet']);
        $valueMap = [
            ['session', $sessionMock],
            ['dispatcher', $dispatcherMock],
        ];

        $applicationMock = $applicationMockBuilder->getMock();
        $applicationMock->method('offsetGet')->will($this->returnValueMap($valueMap));

        return $applicationMock;
    }

    /**
     * @deprecated Will be removed with next major release.
     *
     * @return void
     */
    public function testCanBeUsedWithSessionHandlerRedis()
    {
        $this->setConfig(SessionConstants::YVES_SESSION_SAVE_HANDLER, SessionConfig::SESSION_HANDLER_REDIS);

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
        $this->setConfig(SessionConstants::YVES_SESSION_SAVE_HANDLER, SessionConfig::SESSION_HANDLER_REDIS_LOCKING);

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
        $this->setConfig(SessionConstants::YVES_SESSION_SAVE_HANDLER, SessionConfig::SESSION_HANDLER_FILE);

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
        $this->setConfig(SessionConstants::YVES_SESSION_SAVE_HANDLER, static::DUMMY_SESSION_HANDLER_NAME);
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
     * @return \Spryker\Yves\Session\Plugin\ServiceProvider\SessionServiceProvider
     */
    protected function createSessionServiceProviderWithFactoryMock(): SessionServiceProvider
    {
        $sessionServiceProvider = new SessionServiceProvider();
        $sessionServiceProviderReflection = new ReflectionClass($sessionServiceProvider);
        $factoryProperty = $sessionServiceProviderReflection->getParentClass()->getProperty('factory');
        $factoryProperty->setAccessible(true);
        $sessionFactoryMock = $this->createSessionFactoryMock();

        $factoryProperty->setValue($sessionServiceProvider, $sessionFactoryMock);

        return $sessionServiceProvider;
    }

    /**
     * @return \Spryker\Yves\Session\SessionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSessionFactoryMock()
    {
        $sessionFactoryMock = $this->getMockBuilder(SessionFactory::class)
            ->setMethods([
                'createSessionHandlerRedis',
                'createSessionHandlerRedisLocking',
                'createSessionHandlerFile',
                'getSessionHandlerPlugins',
                'getMonitoringService',
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
            new SessionConfigYves()
        );

        return $sessionFactoryMock;
    }
}
