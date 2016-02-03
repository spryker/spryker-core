<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Maintenance;

use Spryker\Zed\Maintenance\MaintenanceConfig;

class MaintenanceConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Maintenance\MaintenanceConfig
     */
    private function getConfig()
    {
        return new MaintenanceConfig();
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
