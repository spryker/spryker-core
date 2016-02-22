<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
