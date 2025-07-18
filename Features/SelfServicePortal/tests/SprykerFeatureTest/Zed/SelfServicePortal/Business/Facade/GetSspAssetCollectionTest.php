<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Facade
 * @group GetSspAssetCollectionTest
 */
class GetSspAssetCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE_CURRENT = 'LOCALE_CURRENT';

    /**
     * @var string
     */
    protected const PLUGINS_PERMISSION = 'PLUGINS_PERMISSION';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade
     */
    protected SelfServicePortalFacade $selfServicePortalFacade;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->selfServicePortalFacade = new SelfServicePortalFacade();

        $this->tester->setDependency(static::LOCALE_CURRENT, 'en_US');
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());
        $this->tester->setDependency(static::PLUGINS_PERMISSION, [
            new ViewCompanySspAssetPermissionPlugin(),
            new ViewBusinessUnitSspAssetPermissionPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testGetSspAssetCollectionReturnsAssetsWithBusinessUnitSspAssetPermission(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $permissionTransfer = $this->tester->havePermission(new ViewBusinessUnitSspAssetPermissionPlugin());
        $permissionCollectionTransfer = (new PermissionCollectionTransfer())->addPermission($permissionTransfer);
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions($companyTransfer, $permissionCollectionTransfer);
        $businessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => 'Test Asset',
            SspAssetTransfer::SERIAL_NUMBER => 'TEST-123',
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
        ]);

        // Create criteria with ViewBusinessUnitSspAssetPermissionPlugin
        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setInclude((new SspAssetIncludeTransfer())
                ->setWithAssignedBusinessUnits(true));

        // Act
        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspAssetCollectionTransfer::class, $sspAssetCollectionTransfer);
        $this->assertCount(1, $sspAssetCollectionTransfer->getSspAssets());
        $this->assertSame('Test Asset', $sspAssetCollectionTransfer->getSspAssets()[0]->getName());
    }

    /**
     * @return void
     */
    public function testGetSspAssetCollectionReturnsAssetsWithCompanySspAssetPermission(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $permissionTransfer = $this->tester->havePermission(new ViewCompanySspAssetPermissionPlugin());
        $permissionCollectionTransfer = (new PermissionCollectionTransfer())->addPermission($permissionTransfer);
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions($companyTransfer, $permissionCollectionTransfer);
        $businessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => 'Test Asset',
            SspAssetTransfer::SERIAL_NUMBER => 'TEST-123',
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
        ]);

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setInclude((new SspAssetIncludeTransfer())
                ->setWithAssignedBusinessUnits(true));

        // Act
        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspAssetCollectionTransfer::class, $sspAssetCollectionTransfer);
        $this->assertCount(1, $sspAssetCollectionTransfer->getSspAssets());
        $this->assertSame('Test Asset', $sspAssetCollectionTransfer->getSspAssets()[0]->getName());
    }

    /**
     * @return void
     */
    public function testGetSspAssetCollectionReturnsEmptyCollectionWithoutPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions($companyTransfer, $permissionCollectionTransfer);
        $businessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => 'Test Asset',
            SspAssetTransfer::SERIAL_NUMBER => 'TEST-123',
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
        ]);

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer);

        // Act
        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspAssetCollectionTransfer::class, $sspAssetCollectionTransfer);
        $this->assertCount(0, $sspAssetCollectionTransfer->getSspAssets());
    }

    /**
     * @return void
     */
    public function testGetSspAssetCollectionReturnsSpecificAssetWithPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        $permissionCollectionTransfer->addPermission($this->tester->havePermission(new ViewBusinessUnitSspAssetPermissionPlugin()));
        $permissionCollectionTransfer->addPermission($this->tester->havePermission(new ViewCompanySspAssetPermissionPlugin()));
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions($companyTransfer, $permissionCollectionTransfer);
        $businessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();

        $sspAssetTransfer1 = $this->tester->haveAsset([
            SspAssetTransfer::NAME => 'Test Asset 1',
            SspAssetTransfer::SERIAL_NUMBER => 'TEST-123',
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
        ]);

        $sspAssetTransfer2 = $this->tester->haveAsset([
            SspAssetTransfer::NAME => 'Test Asset 2',
            SspAssetTransfer::SERIAL_NUMBER => 'TEST-456',
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
        ]);

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setSspAssetConditions((new SspAssetConditionsTransfer())
                ->addIdSspAsset($sspAssetTransfer1->getIdSspAsset()))
            ->setInclude((new SspAssetIncludeTransfer())
                ->setWithAssignedBusinessUnits(true));

        // Act
        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspAssetCollectionTransfer::class, $sspAssetCollectionTransfer);
        $this->assertCount(1, $sspAssetCollectionTransfer->getSspAssets());
        $this->assertSame('Test Asset 1', $sspAssetCollectionTransfer->getSspAssets()[0]->getName());
    }
}
