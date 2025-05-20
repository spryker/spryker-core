<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\SessionRedis\Plugin\Session;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedis;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisLocking;
use Spryker\Shared\SessionRedis\SessionRedisConfig;
use Spryker\Yves\SessionRedis\Plugin\Session\SessionHandlerConfigurableRedisLockingProviderPlugin;
use Spryker\Yves\SessionRedis\SessionRedisDependencyProvider;
use Spryker\Yves\SessionRedisExtension\Dependency\Plugin\SessionRedisLockingExclusionConditionPluginInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SessionRedis
 * @group Plugin
 * @group Session
 * @group SessionHandlerConfigurableRedisLockingProviderPluginTest
 * Add your own group annotations below this line
 */
class SessionHandlerConfigurableRedisLockingProviderPluginTest extends Unit
{
    /**
     * @var \Spryker\Yves\SessionRedis\Plugin\Session\SessionHandlerConfigurableRedisLockingProviderPlugin
     */
    protected $sessionHandlerPlugin;

    /**
     * @var \SprykerTest\Yves\SessionRedis\SessionRedisYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $redisClientMock = $this->getMockBuilder(SessionRedisToRedisClientInterface::class)->getMock();
        $this->tester->setDependency(SessionRedisDependencyProvider::CLIENT_REDIS, $redisClientMock);
        $this->tester->setDependency(SessionRedisDependencyProvider::REQUEST_STACK, new RequestStack());

        $this->sessionHandlerPlugin = new SessionHandlerConfigurableRedisLockingProviderPlugin();
    }

    /**
     * @return void
     */
    public function testHasCorrectSessionHandlerName(): void
    {
        $this->assertSame(
            $this->getSharedConfig()->getSessionHandlerConfigurableRedisLockingName(),
            $this->sessionHandlerPlugin->getSessionHandlerName(),
        );
    }

    /**
     * @return void
     */
    public function testPluginReturnsCorrectLockingSessionHandler(): void
    {
        $this->assertInstanceOf(SessionHandlerRedisLocking::class, $this->sessionHandlerPlugin->getSessionHandler());
    }

    /**
     * @return void
     */
    public function testPluginReturnsCorrectNonLockingSessionHandler(): void
    {
        $sessionRedisLockingExclusionConditionPluginMock = $this->getMockBuilder(SessionRedisLockingExclusionConditionPluginInterface::class)
            ->getMock();

        $sessionRedisLockingExclusionConditionPluginMock->method('checkCondition')
            ->willReturn(true);

        $this->tester->setDependency(
            SessionRedisDependencyProvider::PLUGINS_SESSION_REDIS_LOCKING_EXCLUSION_CONDITION,
            [
                $sessionRedisLockingExclusionConditionPluginMock,
            ],
        );

        $this->assertInstanceOf(SessionHandlerRedis::class, $this->sessionHandlerPlugin->getSessionHandler());
    }

    /**
     * @return \Spryker\Shared\SessionRedis\SessionRedisConfig
     */
    protected function getSharedConfig(): SessionRedisConfig
    {
        return new SessionRedisConfig();
    }
}
