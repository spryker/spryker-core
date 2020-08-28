<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SessionRedis\Communication\Plugin\Session;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisLocking;
use Spryker\Shared\SessionRedis\SessionRedisConfig;
use Spryker\Zed\SessionRedis\Communication\Plugin\Session\SessionHandlerRedisLockingProviderPlugin;
use Spryker\Zed\SessionRedis\SessionRedisDependencyProvider;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SessionRedis
 * @group Communication
 * @group Plugin
 * @group Session
 * @group SessionHandlerRedisLockingProviderPluginTest
 * Add your own group annotations below this line
 */
class SessionHandlerRedisLockingProviderPluginTest extends Unit
{
    /**
     * @var \Spryker\Zed\SessionRedis\Communication\Plugin\Session\SessionHandlerRedisLockingProviderPlugin
     */
    protected $sessionHandlerPlugin;

    /**
     * @var \SprykerTest\Zed\SessionRedis\SessionRedisCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(SessionRedisDependencyProvider::REQUEST_STACK, new RequestStack());
        $this->sessionHandlerPlugin = new SessionHandlerRedisLockingProviderPlugin();
    }

    /**
     * @return void
     */
    public function testHasCorrectSessionHandlerName(): void
    {
        $this->assertEquals($this->getSharedConfig()->getSessionHandlerRedisLockingName(), $this->sessionHandlerPlugin->getSessionHandlerName());
    }

    /**
     * @return void
     */
    public function testPluginReturnsCorrectSessionHandler(): void
    {
        $this->assertInstanceOf(SessionHandlerRedisLocking::class, $this->sessionHandlerPlugin->getSessionHandler());
    }

    /**
     * @return \Spryker\Shared\SessionRedis\SessionRedisConfig
     */
    protected function getSharedConfig(): SessionRedisConfig
    {
        return new SessionRedisConfig();
    }
}
