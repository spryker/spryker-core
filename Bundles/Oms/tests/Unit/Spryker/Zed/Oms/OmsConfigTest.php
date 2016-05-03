<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Oms;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Zed\Oms\OmsConfig;

/**
 * @group Spryker
 * @group Zed
 * @group Oms
 * @group OmsConfig
 */
class OmsConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetProcessDefinitionLocationReturnDefault()
    {
        $omsConfig = new OmsConfig();
        $reflectionClass = new \ReflectionClass(Config::class);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);

        $this->assertSame(OmsConfig::DEFAULT_PROCESS_LOCATION, $omsConfig->getProcessDefinitionLocation());
    }

    /**
     * @return void
     */
    public function testGetProcessDefinitionLocationReturnConfiguredPath()
    {
        $omsConfig = new OmsConfig();
        $reflectionClass = new \ReflectionClass(Config::class);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([OmsConstants::PROCESS_LOCATIONS => APPLICATION_ROOT_DIR . '/configuredPaths']);

        $this->assertSame(APPLICATION_ROOT_DIR . '/configuredPaths', $omsConfig->getProcessDefinitionLocation());
    }

    /**
     * @return void
     */
    public function testGetProcessDefinitionLocationDefaultPathMustBeAbsolute()
    {
        $omsConfig = new OmsConfig();
        $reflectionClass = new \ReflectionClass(Config::class);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);

        $this->assertStringStartsWith(APPLICATION_ROOT_DIR, $omsConfig->getProcessDefinitionLocation());
    }

}
