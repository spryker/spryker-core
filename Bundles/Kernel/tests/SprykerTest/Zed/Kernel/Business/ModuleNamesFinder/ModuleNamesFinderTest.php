<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Business\ModuleNamesFinder;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Business
 * @group ModuleNamesFinder
 * @group ModuleNamesFinderTest
 * Add your own group annotations below this line
 */
class ModuleNamesFinderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Kernel\KernelZedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindModuleNamesReturnsListOfModuleNames(): void
    {
        $structure = [
            'src' => [
                'Organization' => [
                    'Application' => [
                        $this->tester->getModuleName() => [
                            'Layer' => [
                                $this->tester->getModuleName() . 'Facade.php' => '',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $virtualDirectory = $this->tester->getVirtualDirectory($structure);
        $path = sprintf('%ssrc/Organization/Application/', $virtualDirectory);

        $this->tester->mockConfigMethod('getPathsToProjectModules', [$path]);
        $this->tester->mockConfigMethod('getPathsToCoreModules', [$path]);

        $moduleNameFinder = $this->tester->getFactory()->createModuleNamesFinder();

        $moduleNames = $moduleNameFinder->findModuleNames();

        $this->assertTrue(in_array($this->tester->getModuleName(), $moduleNames));
    }
}
