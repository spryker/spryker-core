<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Spryker\Client\CompanyRole\Plugin\PermissionStoragePlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;

abstract class AbstractCompanyRoleDataImportMock extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyRoleDataImport\CompanyRoleDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new PermissionStoragePlugin(),
        ]);

        $this->tester->prepareTestData();
    }
}
