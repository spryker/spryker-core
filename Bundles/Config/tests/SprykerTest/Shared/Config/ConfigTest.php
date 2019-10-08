<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Config;

use Codeception\Test\Unit;
use Spryker\Shared\Config\Config;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Config
 * @group ConfigTest
 * Add your own group annotations below this line
 */
class ConfigTest extends Unit
{
    /**
     * @return void
     */
    public function testGetInstance()
    {
        $this->assertInstanceOf(Config::class, Config::getInstance());
    }
}
