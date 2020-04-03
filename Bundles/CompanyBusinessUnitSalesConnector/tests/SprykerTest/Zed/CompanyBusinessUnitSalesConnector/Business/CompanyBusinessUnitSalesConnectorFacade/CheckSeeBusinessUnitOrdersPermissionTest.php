<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Zed\Permission\PermissionDependencyProvider;
use Spryker\Shared\CompanyBusinessUnitSalesConnector\Plugin\Permission\SeeBusinessUnitOrdersPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitSalesConnector
 * @group Business
 * @group CompanyBusinessUnitSalesConnectorFacade
 * @group CheckSeeBusinessUnitOrdersPermissionTest
 * Add your own group annotations below this line
 */
class CheckSeeBusinessUnitOrdersPermissionTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_BUSINESS_UUID = 'FAKE_BUSINESS_UUID';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new SeeBusinessUnitOrdersPermissionPlugin(),
        ]);

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();
    }

    /**
     * @return void
     */
    public function testCheckSeeBusinessUnitOrdersPermission(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermission('SeeBusinessUnitOrdersPermissionPlugin');

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyUserTransfer->getFkCompany(),
        ]);

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkSeeBusinessUnitOrdersPermission(
                (new OrderTransfer())->setCompanyBusinessUnitUuid($companyUserTransfer->getCompanyBusinessUnit()->getUuid()),
                (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer)
            );

        // Assert
        $this->assertTrue($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckSeeBusinessUnitOrdersPermissionWithoutCompanyBusinessUuid(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermission('SeeBusinessUnitOrdersPermissionPlugin');

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyUserTransfer->getFkCompany(),
        ]);

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkSeeBusinessUnitOrdersPermission(
                (new OrderTransfer())->setCompanyBusinessUnitUuid(null),
                (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer)
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckSeeBusinessUnitOrdersPermissionWithoutCompanyUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermission('SeeBusinessUnitOrdersPermissionPlugin');

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyUserTransfer->getFkCompany(),
        ]);

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkSeeBusinessUnitOrdersPermission(
                (new OrderTransfer())->setCompanyBusinessUnitUuid($companyUserTransfer->getCompanyBusinessUnit()->getUuid()),
                (new CustomerTransfer())->setCompanyUserTransfer(null)
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckSeeBusinessUnitOrdersPermissionWithoutIdCompanyUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermission('SeeBusinessUnitOrdersPermissionPlugin');

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyUserTransfer->getFkCompany(),
        ]);

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkSeeBusinessUnitOrdersPermission(
                (new OrderTransfer())->setCompanyBusinessUnitUuid($companyUserTransfer->getCompanyBusinessUnit()->getUuid()),
                (new CustomerTransfer())->setCompanyUserTransfer(new CompanyUserTransfer())
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckSeeBusinessUnitOrdersPermissionWithoutCompanyBusinessUnit(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermission('SeeBusinessUnitOrdersPermissionPlugin');

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyUserTransfer->getFkCompany(),
        ]);

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkSeeBusinessUnitOrdersPermission(
                (new OrderTransfer())->setCompanyBusinessUnitUuid($companyUserTransfer->getCompanyBusinessUnit()->getUuid()),
                (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer->setCompanyBusinessUnit(null))
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckSeeBusinessUnitOrdersPermissionWithDifferentBusinessUnitUuids(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermission('SeeBusinessUnitOrdersPermissionPlugin');

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyUserTransfer->getFkCompany(),
        ]);

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkSeeBusinessUnitOrdersPermission(
                (new OrderTransfer())->setCompanyBusinessUnitUuid(static::FAKE_BUSINESS_UUID),
                (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer)
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }
}
