<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Development;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Development\DevelopmentConfig;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Development
 * @group DevelopmentConfigTest
 */
class DevelopmentConfigTest extends PHPUnit_Framework_TestCase
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
    public function testGetPathToCore()
    {
        $this->assertTrue(is_string($this->getConfig()->getPathToCore()));
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
    public function testGetPathToComposerLockShouldReturnString()
    {
        $this->assertTrue(is_string($this->getConfig()->getPathToComposerLock()));
    }

    /**
     * @return void
     */
    public function testGetArchitectureSnifferDefaultPriority()
    {
        $this->assertSame(2, $this->getConfig()->getArchitectureSnifferDefaultPriority());
    }

}
