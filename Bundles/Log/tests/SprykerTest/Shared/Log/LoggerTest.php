<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Log;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Spryker\Shared\Log\LoggerTrait;
use SprykerTest\Shared\Log\Fixtures\TestLoggerConfig;
use SprykerTest\Shared\Log\Fixtures\TestLoggerConfig2;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Log
 * @group LoggerTest
 * Add your own group annotations below this line
 */
class LoggerTest extends Unit
{
    use LoggerTrait;

    /**
     * @return void
     */
    public function testGetLoggerWithoutConfigShouldReturnDefaultLoggerInstance()
    {
        $this->assertInstanceOf(LoggerInterface::class, $this->getLogger());
    }

    /**
     * @return void
     */
    public function testGetLoggerWithSameConfigShouldReturnTheSameLoggerInstance()
    {
        $logger1 = $this->getLogger(new TestLoggerConfig());
        $logger2 = $this->getLogger(new TestLoggerConfig());

        $this->assertSame($logger1, $logger2);
    }

    /**
     * @return void
     */
    public function testGetLoggerWithDifferentConfigShouldReturnDifferentLoggerInstances()
    {
        $logger1 = $this->getLogger(new TestLoggerConfig());
        $logger2 = $this->getLogger(new TestLoggerConfig2());

        $this->assertNotSame($logger1, $logger2);
    }
}
