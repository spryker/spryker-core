<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Config\Application;

use Codeception\Test\Unit;
use Spryker\Shared\Config\Application\Environment;
use SprykerTest\Shared\Config\ConfigSharedTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Config
 * @group Application
 * @group EnvironmentTest
 * Add your own group annotations below this line
 */
class EnvironmentTest extends Unit
{
    /**
     * @var \SprykerTest\Shared\Config\ConfigSharedTester
     */
    protected ConfigSharedTester $tester;

    /**
     * @return void
     */
    public function testInitializeDefinesStore(): void
    {
        // Assign
        $store = defined('APPLICATION_STORE');

        // Act
        Environment::initialize();

        // Assert
        $this->assertSame(!$store && $this->tester->isDynamicStoreEnabled(), !defined('APPLICATION_STORE'));
    }

    /**
     * @return void
     */
    public function testInitializeDefinesRegion(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('This test requires DynamicStore to be enabled.');
        }

        // Act
        Environment::initialize();

        // Assert
        $this->assertTrue(defined('APPLICATION_REGION'));
        $this->assertIsString(APPLICATION_REGION);
    }
}
