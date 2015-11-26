<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Development;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Development\DevelopmentConfig;

class DevelopmentConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return DevelopmentConfig
     */
    private function getConfig()
    {
        return new DevelopmentConfig(Config::getInstance(), $this->getLocator());
    }

    /**
     * @return Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return void
     */
    public function testGetPathToRoot()
    {
        $this->assertTrue(is_string($this->getConfig()->getPathToRoot()));
    }


    /**
     * @return void
     */
    public function testGetPathToSpryker()
    {
        $this->assertTrue(is_string($this->getConfig()->getPathToSpryker()));
    }

}
