<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Permission\SeeBusinessUnitOrdersPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\SeeCompanyOrdersPermissionPlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitSalesConnector
 * @group Business
 * @group CompanyBusinessUnitSalesConnectorFacade
 * @group GetPermittedCompanyBusinessUnitCollectionTest
 * Add your own group annotations below this line
 */
class GetPermittedCompanyBusinessUnitCollectionTest extends Unit
{
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

        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new SeeCompanyOrdersPermissionPlugin(),
            new SeeBusinessUnitOrdersPermissionPlugin(),
        ]);

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();
    }

    /**
     * @return void
     */
    public function testGetPermittedCompanyBusinessUnitCollectionThrowsExceptionOnMissingIdCompanyUser(): void
    {
        // Arrange
        $companyUserTransfer = new CompanyUserTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->getPermittedCompanyBusinessUnitCollection($companyUserTransfer);
    }

    /**
     * @return void
     */
    public function testGetPermittedCompanyBusinessUnitCollectionReturnsSingleBusinessUnit(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermission('SeeBusinessUnitOrdersPermissionPlugin');

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyUserTransfer->getFkCompany(),
        ]);

        // Act
        $companyBusinessUnitCollectionTransfer = $this->tester->getFacade()
            ->getPermittedCompanyBusinessUnitCollection($companyUserTransfer);

        // Assert
        $this->assertCount(1, $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits());
        $this->assertSame(
            $companyUserTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
            $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()->getIterator()->current()->getIdCompanyBusinessUnit()
        );
    }

    /**
     * @return void
     */
    public function testGetPermittedCompanyBusinessUnitCollectionReturnsCompanyBusinessUnits(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermission('SeeCompanyOrdersPermissionPlugin');

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyUserTransfer->getFkCompany(),
        ]);

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $companyBusinessUnitCollectionTransfer = $this->tester->getFacade()
            ->getPermittedCompanyBusinessUnitCollection($companyUserTransfer);

        // Assert
        $this->assertCount(2, $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits());
    }
}
