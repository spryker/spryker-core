<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Development\Business\DevelopmentFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group Facade
 * @group DevelopmentFacadeTest
 * Add your own group annotations below this line
 */
class DevelopmentFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindsModules(): void
    {
        $moduleTransferCollection = $this->getFacade()->findModules();

        $this->assertInternalType('array', $moduleTransferCollection);
    }

    /**
     * @dataProvider moduleFilterDataProvider
     *
     * @param string $organizationName
     * @param string $moduleName
     * @param int $expectedModuleCount
     *
     * @return void
     */
    public function testFindsModulesWithModuleFilter(string $organizationName, string $moduleName, int $expectedModuleCount): void
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName($organizationName);

        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer->setName($moduleName);

        $moduleFilterTransfer = new ModuleFilterTransfer();
        $moduleFilterTransfer
            ->setOrganization($organizationTransfer)
            ->setModule($moduleTransfer);

        $moduleTransferCollection = $this->getFacade()->findModules($moduleFilterTransfer);

        $this->assertInternalType('array', $moduleTransferCollection);
        $this->assertCount($expectedModuleCount, $moduleTransferCollection);

        if ($expectedModuleCount === 1) {
            $this->assertArrayHasKey('Spryker.Development', $moduleTransferCollection);
        }
    }

    /**
     * @return array
     */
    public function moduleFilterDataProvider(): array
    {
        return [
            ['Spryker', 'Development', 1],
            ['Spryke*', 'Development', 1],
            ['*pryker', 'Development', 1],
            ['Spryker', 'Developmen*', 2],
            ['Spryker', '*evelopment', 1],
        ];
    }

    /**
     * @return void
     */
    public function testFindsProjectModules(): void
    {
        $moduleTransferCollection = $this->getFacade()->findProjectModules();

        $this->assertInternalType('array', $moduleTransferCollection);
    }

    /**
     * @return void
     */
    public function testFindsPackages(): void
    {
        $packageTransferCollection = $this->getFacade()->findPackages();

        $this->assertInternalType('array', $packageTransferCollection);
    }

    /**
     * @return \Spryker\Zed\Development\Business\DevelopmentFacadeInterface
     */
    protected function getFacade(): DevelopmentFacadeInterface
    {
        return $this->tester->getFacade();
    }
}
