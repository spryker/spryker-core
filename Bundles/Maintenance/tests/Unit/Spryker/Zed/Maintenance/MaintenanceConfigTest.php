<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

}
