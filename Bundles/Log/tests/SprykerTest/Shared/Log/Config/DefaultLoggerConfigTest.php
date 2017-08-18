<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Log\Config;

use Codeception\Test\Unit;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Log\Config\DefaultLoggerConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Log
 * @group Config
 * @group DefaultLoggerConfigTest
 * Add your own group annotations below this line
 */
class DefaultLoggerConfigTest extends Unit
{

    /**
     * @return void
     */
    public function testGetChannelNameShouldReturnString()
    {
        $defaultLoggerConfig = new DefaultLoggerConfig();

        $this->assertInternalType('string', $defaultLoggerConfig->getChannelName());
    }

    /**
     * @return void
     */
    public function testGetHandlersShouldReturnArray()
    {
        $defaultLoggerConfig = new DefaultLoggerConfig();

        $handler = $defaultLoggerConfig->getHandlers();
        $this->assertInternalType('array', $handler);
    }

    /**
     * @return void
     */
    public function testGetProcessorsShouldReturnArray()
    {
        $defaultLoggerConfig = new DefaultLoggerConfig();
        $this->assertInternalType('array', $defaultLoggerConfig->getProcessors());
    }

}
