<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared;

use Spryker\Shared\Config as DeprecatedConfig;
use Spryker\Shared\Config\Config;

/**
 * @group Spryker
 * @group Shared
 * @group Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstanceMustTriggerErrorAndReturnConfigInstance()
    {
        $this->setExpectedException(\ErrorException::class);

        $config = DeprecatedConfig::getInstance();

        $this->assertInstanceOf(Config::class, $config);
    }


    public function testInitMustTriggerError()
    {
        $this->setExpectedException(\ErrorException::class);

        DeprecatedConfig::init();
    }

}
