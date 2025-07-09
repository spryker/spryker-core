<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemWriterPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Shared\FileSystem\FileSystemConstants;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\SspDashboardManagement\SspFileDashboardDataExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Facade
 * @group ProvideDashboardDataFacadeTest
 * Add your own group annotations below this line
 */
class ProvideDashboardDataFacadeTest extends Unit
{
 /**
  * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
  */
    protected SelfServicePortalBusinessTester $tester;

    /**
     * @var string
     */
    protected const PLUGIN_WRITER = 'PLUGIN_WRITER';

    /**
     * @var string
     */
    public const PLUGIN_COLLECTION_FILESYSTEM_BUILDER = 'filesystem builder plugin collection';

    /**
     * @var string
     */
    public const PLUGINS_PERMISSION = 'PLUGINS_PERMISSION';

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->tester->setDependency(static::PLUGIN_WRITER, new FileSystemWriterPlugin());
        $this->tester->setDependency(static::PLUGIN_COLLECTION_FILESYSTEM_BUILDER, [
            new LocalFilesystemBuilderPlugin(),
        ]);

        $localFilesystemBuilderConfiguration = [
            'sprykerAdapterClass' => LocalFilesystemBuilderPlugin::class,
            'root' => '/data/data/tmp/ssp-files',
            'path' => '/',
        ];

        $this->tester->setConfig(FileSystemConstants::FILESYSTEM_SERVICE, [
            's3-import' => $localFilesystemBuilderConfiguration,
            'files' => $localFilesystemBuilderConfiguration,
        ]);

        $this->tester->ensureFileAttachmentTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testProvideDashboardDataWillSucceed(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $companyUserTransfer = $this->tester->haveCompanyUser(
            [
                CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
                CompanyUserTransfer::COMPANY => $companyTransfer,
                CompanyUserTransfer::CUSTOMER => $customerTransfer,
            ],
        );

        $this->tester->haveCompanyUserFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompanyUser' => $companyUserTransfer->getIdCompanyUserOrFail(),
        ]);

        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setCompanyUser($companyUserTransfer);

        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            'fkCompany' => $companyTransfer->getIdCompanyOrFail(),
            'company' => $companyTransfer,
        ]);

        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);
        $dashboardResponseTransfer = (new DashboardResponseTransfer());

        // Act
        $actualDashboardResponseTransfer = (new SspFileDashboardDataExpanderPlugin())->provideDashboardData($dashboardResponseTransfer, $dashboardRequestTransfer);

        // Assert
        $this->assertIsObject($actualDashboardResponseTransfer->getDashboardComponentFiles());
        $this->assertCount(1, $actualDashboardResponseTransfer->getDashboardComponentFiles()->getFileAttachmentCollection()->getFileAttachments());
    }

    /**
     * @return void
     */
    public function testProvideDashboardDataWillNotReturnFilesOnCompanySharedFileWithoutRelevantPermission(): void
    {
        // Arrange
        $this->tester->setDependency(static::PLUGINS_PERMISSION, [
            new ViewCompanyFilesPermissionPlugin(),
            new ViewCompanyBusinessUnitFilesPermissionPlugin(),
            new ViewCompanyUserFilesPermissionPlugin(),
            new ViewCompanySspAssetPermissionPlugin(),
            new ViewBusinessUnitSspAssetPermissionPlugin(),
        ]);

        $companyTransfer = $this->tester->haveCompany();
        $fileTransfer = $this->tester->haveFile();

        $companyUserTransfer = $this->tester->haveCompanyUser(
            [
                CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
                CompanyUserTransfer::COMPANY => $companyTransfer,
                CompanyUserTransfer::CUSTOMER => (new CustomerTransfer())->setEmail('test@test.test'),
            ],
        );

        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            'fkCompany' => $companyTransfer->getIdCompanyOrFail(),
            'company' => $companyTransfer,
        ]);

        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);

        $this->tester->haveCompanyFileAttachment([
            'idFile' => $fileTransfer->getIdFileOrFail(),
            'idCompany' => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setCompanyUser($companyUserTransfer);
        $dashboardResponseTransfer = (new DashboardResponseTransfer());

        // Act
        $actualDashboardResponseTransfer = (new SspFileDashboardDataExpanderPlugin())->provideDashboardData($dashboardResponseTransfer, $dashboardRequestTransfer);

        // Assert
        $this->assertIsObject($actualDashboardResponseTransfer->getDashboardComponentFiles());
        $this->assertCount(0, $actualDashboardResponseTransfer->getDashboardComponentFiles()->getFileAttachmentCollection()->getFileAttachments());
    }
}
