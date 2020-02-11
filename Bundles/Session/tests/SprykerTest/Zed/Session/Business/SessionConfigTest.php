<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Session\Business;

use Codeception\Test\Unit;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Zed\Session\SessionConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Session
 * @group Business
 * @group SessionConfigTest
 * Add your own group annotations below this line
 */
class SessionConfigTest extends Unit
{
    public const EXPECTED_DSN_TCP_WITHOUT_PASSWORD = 'tcp://10.10.0.1:6435?database=0';
    public const EXPECTED_DSN_TCP_WITH_PASSWORD = 'tcp://10.10.0.1:6435?database=0&password=secret';

    public const EXPECTED_DSN_REDIS_WITHOUT_PASSWORD = 'redis://10.10.0.1:6435/0';
    public const EXPECTED_DSN_REDIS_WITH_PASSWORD = 'redis://:secret@10.10.0.1:6435/0';

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setConfig(SessionConstants::ZED_SESSION_REDIS_HOST, '10.10.0.1');
        $this->tester->setConfig(SessionConstants::ZED_SESSION_REDIS_PORT, '6435');
        $this->tester->setConfig(SessionConstants::ZED_SESSION_REDIS_DATABASE, 0);

        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_HOST, '10.10.0.1');
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PORT, '6435');
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_DATABASE, 0);
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameZedForTcpProtocolWithoutPassword(): void
    {
        $this->tester->setConfig(SessionConstants::ZED_SESSION_REDIS_PROTOCOL, 'tcp');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_TCP_WITHOUT_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceNameZed());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameZedForTcpProtocolWithPassword(): void
    {
        $this->tester->setConfig(SessionConstants::ZED_SESSION_REDIS_PROTOCOL, 'tcp');
        $this->tester->setConfig(SessionConstants::ZED_SESSION_REDIS_PASSWORD, 'secret');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_TCP_WITH_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceNameZed());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameZedForRedisProtocolWithoutPassword(): void
    {
        $this->tester->setConfig(SessionConstants::ZED_SESSION_REDIS_PROTOCOL, 'redis');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_REDIS_WITHOUT_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceNameZed());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameZedForRedisProtocolWithPassword(): void
    {
        $this->tester->setConfig(SessionConstants::ZED_SESSION_REDIS_PROTOCOL, 'redis');
        $this->tester->setConfig(SessionConstants::ZED_SESSION_REDIS_PASSWORD, 'secret');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_REDIS_WITH_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceNameZed());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameYvesForTcpProtocolWithoutPassword(): void
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PROTOCOL, 'tcp');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_TCP_WITHOUT_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceNameYves());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameYvesForTcpProtocolWithPassword(): void
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PROTOCOL, 'tcp');
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PASSWORD, 'secret');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_TCP_WITH_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceNameYves());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameYvesForRedisProtocolWithoutPassword(): void
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PROTOCOL, 'redis');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_REDIS_WITHOUT_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceNameYves());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameYvesForRedisProtocolWithPassword(): void
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PROTOCOL, 'redis');
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PASSWORD, 'secret');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_REDIS_WITH_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceNameYves());
    }
}
