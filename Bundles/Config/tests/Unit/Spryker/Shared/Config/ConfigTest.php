<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Config;

use Spryker\Shared\Config\Config;

/**
 * @group Spryker
 * @group Shared
 * @group Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstance()
    {
        $this->assertInstanceOf(Config::class, Config::getInstance());
    }

}
