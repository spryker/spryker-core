<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ModuleFinder\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ModuleFinder
 * @group Business
 * @group Facade
 * @group ModuleFinderFacadeTest
 * Add your own group annotations below this line
 */
class ModuleFinderFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ModuleFinder\ModuleFinderBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetModulesReturnsProjectAndCoreModules(): void
    {
        $result = $this->tester->getFacade()->getModules();

        $this->assertIsArray($result);
    }

    /**
     * @return void
     */
    public function testGetProjectModulesReturnsProjectModules(): void
    {
        $result = $this->tester->getFacade()->getProjectModules();

        $this->assertIsArray($result);
    }

    /**
     * @return void
     */
    public function testGetPackagesReturnsListOfPackages(): void
    {
        $result = $this->tester->getFacade()->getPackages();

        $this->assertIsArray($result);
    }
}
