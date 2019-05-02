<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Config\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Config
 * @group Business
 * @group Facade
 * @group ConfigFacadeTest
 * Add your own group annotations below this line
 */
class ConfigFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Config\ConfigBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProfileDataReturnsAnArrayWithUsedConfigurations(): void
    {
        $this->assertTrue(is_array($this->tester->getFacade()->getProfileData()));
    }
}
