<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Shared\Log;

use Codeception\TestCase\Test;
use Unit\SprykerFeature\Shared\Log\Fixtures\TestLoggerConfig;
use Psr\Log\LoggerInterface;
use SprykerFeature\Shared\Log\LoggerTrait;

class LoggerTest extends Test
{
    use LoggerTrait;

    public function testGetLoggerWithoutConfigShouldReturnDefaultLoggerInstance()
    {
        $this->assertInstanceOf(LoggerInterface::class, $this->getLogger());
    }

    public function testGetLoggerWithSameConfigShouldReturnTheSameLoggerInstance()
    {
        $logger1 = $this->getLogger(new TestLoggerConfig());
        $logger2 = $this->getLogger(new TestLoggerConfig());

        $this->assertSame($logger1, $logger2);
    }

}
