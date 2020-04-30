<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\SeeCompanyOrdersPermissionPlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanySalesConnector
 * @group Business
 * @group CompanySalesConnectorFacade
 * @group CheckOrderAccessByCustomerCompanyTest
 * Add your own group annotations below this line
 */
class CheckOrderAccessByCustomerCompanyTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_COMPANY_UUID = 'FAKE_COMPANY_UUID';

    /**
     * @var \SprykerTest\Zed\CompanySalesConnector\CompanySalesConnectorBusinessTester
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
            new SeeCompanyOrdersPermissionPlugin(),
        ]);

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();
    }

    /**
     * @return void
     */
    public function testCheckOrderAccessByCustomerCompany(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission('SeeCompanyOrdersPermissionPlugin');

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkOrderAccessByCustomerCompany(
                (new OrderTransfer())->setCompanyUuid($companyUserTransfer->getCompany()->getUuid()),
                (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer)
            );

        // Assert
        $this->assertTrue($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckOrderAccessByCustomerCompanyWithoutCompanyUuid(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission('SeeCompanyOrdersPermissionPlugin');

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkOrderAccessByCustomerCompany(
                (new OrderTransfer())->setCompanyUuid(null),
                (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer)
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckOrderAccessByCustomerCompanyWithoutCompanyUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission('SeeCompanyOrdersPermissionPlugin');

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkOrderAccessByCustomerCompany(
                (new OrderTransfer())->setCompanyUuid($companyUserTransfer->getCompany()->getUuid()),
                (new CustomerTransfer())->setCompanyUserTransfer(null)
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckOrderAccessByCustomerCompanyWithoutIdCompanyUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission('SeeCompanyOrdersPermissionPlugin');

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkOrderAccessByCustomerCompany(
                (new OrderTransfer())->setCompanyUuid($companyUserTransfer->getCompany()->getUuid()),
                (new CustomerTransfer())->setCompanyUserTransfer(new CompanyUserTransfer())
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckOrderAccessByCustomerCompanyWithoutCompany(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission('SeeCompanyOrdersPermissionPlugin');

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkOrderAccessByCustomerCompany(
                (new OrderTransfer())->setCompanyUuid($companyUserTransfer->getCompany()->getUuid()),
                (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer->setCompany(null))
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }

    /**
     * @return void
     */
    public function testCheckOrderAccessByCustomerCompanyWithDifferentCompanyUuids(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission('SeeCompanyOrdersPermissionPlugin');

        // Act
        $isPermissionGranted = $this->tester
            ->getFacade()
            ->checkOrderAccessByCustomerCompany(
                (new OrderTransfer())->setCompanyUuid(static::FAKE_COMPANY_UUID),
                (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer)
            );

        // Assert
        $this->assertFalse($isPermissionGranted);
    }
}
