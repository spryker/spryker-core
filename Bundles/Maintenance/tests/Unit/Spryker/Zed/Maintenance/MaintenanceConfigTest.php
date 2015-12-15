<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Maintenance;

use Spryker\Shared\Config;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Maintenance\MaintenanceConfig;

class MaintenanceConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return MaintenanceConfig
     */
    private function getConfig()
    {
        return new MaintenanceConfig(Config::getInstance(), $this->getLocator());
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
    public function testGetPathToComposerLockShouldReturnString()
    {
        $this->assertTrue(is_string($this->getConfig()->getPathToComposerLock()));
    }

    /**
     * @return void
     */
    public function testGetPathToFossFileShouldReturnString()
    {
        $this->assertTrue(is_string($this->getConfig()->getPathToFossFile()));
    }

}
