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
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemWriterPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Shared\FileSystem\FileSystemConstants;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Permission\SeeBusinessUnitOrdersPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\SeeCompanyOrdersPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\SspDashboardManagement\SspFileDashboardDataExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\SspDashboardManagement\SspServiceDashboardDataExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
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
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::getServiceProductClassName()
     *
     * @var string
     */
    protected const DEFAULT_PRODUCT_CLASS_NAME = 'Service';

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
        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemProductClassDatabaseTablesAreEmpty();
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::PLUGINS_DASHBOARD_DATA_PROVIDER,
            [
                new SspFileDashboardDataExpanderPlugin(),
                new SspServiceDashboardDataExpanderPlugin(),
            ],
        );
    }

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
            ->setCompanyUser($companyUserTransfer)
            ->setCustomer($customerTransfer);

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

        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setCustomer($companyUserTransfer->getCustomer());
        $dashboardResponseTransfer = (new DashboardResponseTransfer());

        // Act
        $actualDashboardResponseTransfer = (new SspFileDashboardDataExpanderPlugin())->provideDashboardData($dashboardResponseTransfer, $dashboardRequestTransfer);

        // Assert
        $this->assertIsObject($actualDashboardResponseTransfer->getDashboardComponentFiles());
        $this->assertCount(0, $actualDashboardResponseTransfer->getDashboardComponentFiles()->getFileAttachmentCollection()->getFileAttachments());
    }

    public function testGetDashboardReturnsEmptyServicesWhenNoServicesExist(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setCustomer($companyUserTransfer->getCustomer())
            ->setWithServicesCount(10);

        // Act
        $actualDashboardResponseTransfer = $this->tester->getFacade()->getDashboard($dashboardRequestTransfer);

        // Assert
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices());
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection());
        $this->assertCount(0, $actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection()->getServices());
        $this->assertEquals(0, $actualDashboardResponseTransfer->getDashboardComponentServices()->getPendingItems());
    }

    public function testGetDashboardReturnsServicesWhenServicesExist(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);

        $salesOrderItemEntity1 = $this->tester->createSalesOrderItemForOrder(
            $saveOrderTransfer->getIdSalesOrderOrFail(),
            ['process' => static::DEFAULT_OMS_PROCESS_NAME],
        );
        $salesOrderItemEntity2 = $this->tester->createSalesOrderItemForOrder(
            $saveOrderTransfer->getIdSalesOrderOrFail(),
            ['process' => static::DEFAULT_OMS_PROCESS_NAME],
        );

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            'name' => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);

        $this->tester->haveSalesOrderItemToProductClass(
            $salesOrderItemEntity1->getIdSalesOrderItem(),
            $salesProductClassTransfer->getIdSalesProductClassOrFail(),
        );
        $this->tester->haveSalesOrderItemToProductClass(
            $salesOrderItemEntity2->getIdSalesOrderItem(),
            $salesProductClassTransfer->getIdSalesProductClassOrFail(),
        );

        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setCustomer($companyUserTransfer->getCustomer())
            ->setWithServicesCount(10);

        // Act
        $actualDashboardResponseTransfer = $this->tester->getFacade()->getDashboard($dashboardRequestTransfer);

        // Assert
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices());
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection());
        $this->assertCount(2, $actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection()->getServices());
        $this->assertEquals(2, $actualDashboardResponseTransfer->getDashboardComponentServices()->getPendingItems());
    }

    public function testGetDashboardRespectsWithServicesCountLimit(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            'name' => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder(
                $saveOrderTransfer->getIdSalesOrderOrFail(),
                ['process' => static::DEFAULT_OMS_PROCESS_NAME],
            );
            $this->tester->haveSalesOrderItemToProductClass(
                $salesOrderItemEntity->getIdSalesOrderItem(),
                $salesProductClassTransfer->getIdSalesProductClassOrFail(),
            );
        }

        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setCustomer($companyUserTransfer->getCustomer())
            ->setWithServicesCount(3);

        // Act
        $actualDashboardResponseTransfer = $this->tester->getFacade()->getDashboard($dashboardRequestTransfer);

        // Assert
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices());
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection());
        $this->assertCount(3, $actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection()->getServices());
        $this->assertEquals(5, $actualDashboardResponseTransfer->getDashboardComponentServices()->getPendingItems()); // Total pending items should still be 5
    }

    public function testGetDashboardWithCompanyServicePermissions(): void
    {
        // Arrange
        $this->tester->setDependency(static::PLUGINS_PERMISSION, [
            new SeeCompanyOrdersPermissionPlugin(),
            new SeeBusinessUnitOrdersPermissionPlugin(),
        ]);

        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions(false);
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);

        // Create service items
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder(
            $saveOrderTransfer->getIdSalesOrderOrFail(),
            ['process' => static::DEFAULT_OMS_PROCESS_NAME],
        );
        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            'name' => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);
        $this->tester->haveSalesOrderItemToProductClass(
            $salesOrderItemEntity->getIdSalesOrderItem(),
            $salesProductClassTransfer->getIdSalesProductClassOrFail(),
        );

        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setCustomer($companyUserTransfer->getCustomer())
            ->setWithServicesCount(10);

        // Act
        $actualDashboardResponseTransfer = $this->tester->getFacade()->getDashboard($dashboardRequestTransfer);

        // Assert
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices());
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection());
        $this->assertCount(1, $actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection()->getServices());
        $this->assertEquals(1, $actualDashboardResponseTransfer->getDashboardComponentServices()->getPendingItems());
    }

    public function testGetDashboardWithBusinessUnitServicePermissions(): void
    {
        // Arrange
        $this->tester->setDependency(static::PLUGINS_PERMISSION, [
            new SeeCompanyOrdersPermissionPlugin(),
            new SeeBusinessUnitOrdersPermissionPlugin(),
        ]);

        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions(false);
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);

        // Create service items
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder(
            $saveOrderTransfer->getIdSalesOrderOrFail(),
            ['process' => static::DEFAULT_OMS_PROCESS_NAME],
        );
        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            'name' => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);
        $this->tester->haveSalesOrderItemToProductClass(
            $salesOrderItemEntity->getIdSalesOrderItem(),
            $salesProductClassTransfer->getIdSalesProductClassOrFail(),
        );

        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setCustomer($companyUserTransfer->getCustomer())
            ->setWithServicesCount(10);

        // Act
        $actualDashboardResponseTransfer = $this->tester->getFacade()->getDashboard($dashboardRequestTransfer);

        // Assert
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices());
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection());
        $this->assertCount(1, $actualDashboardResponseTransfer->getDashboardComponentServices()->getSspServiceCollection()->getServices());
        $this->assertEquals(1, $actualDashboardResponseTransfer->getDashboardComponentServices()->getPendingItems());
    }

    public function testGetDashboardPreservesExistingDashboardResponseData(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setCustomer($companyUserTransfer->getCustomer())
            ->setWithServicesCount(10);

        // Act
        $actualDashboardResponseTransfer = $this->tester->getFacade()->getDashboard($dashboardRequestTransfer);

        // Assert
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentServices());
        $this->assertNotNull($actualDashboardResponseTransfer->getDashboardComponentFiles());
    }

    protected function haveCompanyWithUserWithPermissions(bool $withPermissions = true): CompanyUserTransfer
    {
        $companyTransfer = $this->tester->haveCompany();

        if ($withPermissions) {
            $seeCompanyOrdersPermissionTransfer = $this->tester->havePermission(new SeeCompanyOrdersPermissionPlugin());
            $seeBusinessUnitOrdersPermissionTransfer = $this->tester->havePermission(new SeeBusinessUnitOrdersPermissionPlugin());

            $permissionCollectionTransfer = (new PermissionCollectionTransfer())
                ->addPermission($seeCompanyOrdersPermissionTransfer)
                ->addPermission($seeBusinessUnitOrdersPermissionTransfer);
        }

        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
            $companyTransfer,
            $permissionCollectionTransfer ?? new PermissionCollectionTransfer(),
        );

        return $companyUserTransfer;
    }

    protected function haveCompanyUserOrder(CompanyUserTransfer $companyUserTransfer): SaveOrderTransfer
    {
        $saveOrderTransfer = $this->tester->haveOrder($companyUserTransfer->toArray(), static::DEFAULT_OMS_PROCESS_NAME);
        $spySalesOrder = SpySalesOrderQuery::create()->findPk($saveOrderTransfer->getIdSalesOrderOrFail());
        $spySalesOrder->setCompanyBusinessUnitUuid($companyUserTransfer->getCompanyBusinessUnit()->getUuid());
        $spySalesOrder->setCompanyUuid($companyUserTransfer->getCompany()->getUuid());
        $spySalesOrder->setCustomerReference($companyUserTransfer->getCustomer()->getCustomerReference());
        $spySalesOrder->save();

        return $saveOrderTransfer;
    }
}
