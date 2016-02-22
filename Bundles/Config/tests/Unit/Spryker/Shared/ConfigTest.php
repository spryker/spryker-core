<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

    /**
     * @return void
     */
    public function testGetInstanceMustTriggerErrorAndReturnConfigInstance()
    {
        $this->setExpectedException(\ErrorException::class);

        $config = DeprecatedConfig::getInstance();

        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @return void
     */
    public function testInitMustTriggerError()
    {
        $this->setExpectedException(\ErrorException::class);

        DeprecatedConfig::init();
    }

}
