<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\SessionRedis\Plugin\Session;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisWriteOnlyLocking;
use Spryker\Shared\SessionRedis\SessionRedisConfig;
use Spryker\Yves\SessionRedis\Plugin\Session\SessionHandlerRedisWriteOnlyLockingProviderPlugin;
use Spryker\Yves\SessionRedis\SessionRedisDependencyProvider;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SessionRedis
 * @group Plugin
 * @group Session
 * @group SessionHandlerRedisWriteOnlyLockingProviderPluginTest
 * Add your own group annotations below this line
 */
class SessionHandlerRedisWriteOnlyLockingProviderPluginTest extends Unit
{
    /**
     * @var \Spryker\Yves\SessionRedis\Plugin\Session\SessionHandlerRedisWriteOnlyLockingProviderPlugin
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

        $this->sessionHandlerPlugin = new SessionHandlerRedisWriteOnlyLockingProviderPlugin();
    }

    /**
     * @return void
     */
    public function testHasCorrectSessionHandlerName(): void
    {
        $this->assertSame($this->getSharedConfig()->getSessionHandlerRedisWriteOnlyLockingName(), $this->sessionHandlerPlugin->getSessionHandlerName());
    }

    /**
     * @return void
     */
    public function testPluginReturnsCorrectSessionHandler(): void
    {
        $this->assertInstanceOf(SessionHandlerRedisWriteOnlyLocking::class, $this->sessionHandlerPlugin->getSessionHandler());
    }

    /**
     * @return \Spryker\Shared\SessionRedis\SessionRedisConfig
     */
    protected function getSharedConfig(): SessionRedisConfig
    {
        return new SessionRedisConfig();
    }
}
