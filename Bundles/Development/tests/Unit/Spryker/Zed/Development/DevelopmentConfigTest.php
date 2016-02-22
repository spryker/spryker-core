<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Development;

use Spryker\Zed\Development\DevelopmentConfig;

class DevelopmentConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Development\DevelopmentConfig
     */
    private function getConfig()
    {
        return new DevelopmentConfig();
    }

    /**
     * @return void
     */
    public function testGetPathToRoot()
    {
        $this->assertTrue(is_string($this->getConfig()->getPathToRoot()));
    }

}
