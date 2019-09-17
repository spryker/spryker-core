<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Session;

use Codeception\Test\Unit;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Yves\Session\SessionConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Session
 * @group SessionConfigTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\Session\SessionTester $tester
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
    public function setUp()
    {
        parent::setUp();

        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_HOST, '10.10.0.1');
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PORT, '6435');
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_DATABASE, 0);
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameForTcpProtocolWithoutPassword()
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PROTOCOL, 'tcp');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_TCP_WITHOUT_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceName());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameYvesForTcpProtocolWithPassword()
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PROTOCOL, 'tcp');
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PASSWORD, 'secret');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_TCP_WITH_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceName());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameYvesForRedisProtocolWithoutPassword()
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PROTOCOL, 'redis');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_REDIS_WITHOUT_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceName());
    }

    /**
     * @return void
     */
    public function testGetSessionHandlerRedisDataSourceNameYvesForRedisProtocolWithPassword()
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PROTOCOL, 'redis');
        $this->tester->setConfig(SessionConstants::YVES_SESSION_REDIS_PASSWORD, 'secret');

        $sessionConfig = new SessionConfig();
        $this->assertSame(static::EXPECTED_DSN_REDIS_WITH_PASSWORD, $sessionConfig->getSessionHandlerRedisDataSourceName());
    }
}
