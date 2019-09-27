<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\SessionRedis\Plugin\Session;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedis;
use Spryker\Shared\SessionRedis\SessionRedisConfig;
use Spryker\Yves\SessionRedis\Plugin\Session\SessionHandlerRedisProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SessionRedis
 * @group Plugin
 * @group Session
 * @group SessionHandlerRedisProviderPluginTest
 * Add your own group annotations below this line
 */
class SessionHandlerRedisProviderPluginTest extends Unit
{
    /**
     * @var \Spryker\Yves\SessionRedis\Plugin\Session\SessionHandlerRedisProviderPlugin
     */
    protected $sessionHandlerPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sessionHandlerPlugin = new SessionHandlerRedisProviderPlugin();
    }

    /**
     * @return void
     */
    public function testHasCorrectSessionHandlerName(): void
    {
        $this->assertEquals($this->getSharedConfig()->getSessionHandlerRedisName(), $this->sessionHandlerPlugin->getSessionHandlerName());
    }

    /**
     * @return void
     */
    public function testPluginReturnsCorrectSessionHandler(): void
    {
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
