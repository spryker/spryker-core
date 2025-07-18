<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Asset\Permission;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission\SspAssetCustomerPermissionExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Asset
 * @group Permission
 * @group SspAssetCustomerPermissionExpanderTest
 */
class SspAssetCustomerPermissionExpanderTest extends Unit
{
    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission\SspAssetCustomerPermissionExpander|\PHPUnit\Framework\MockObject\MockObject
     */
    protected SspAssetCustomerPermissionExpander|MockObject $expander;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->expander = $this->createPartialMock(SspAssetCustomerPermissionExpander::class, ['can']);
    }

    /**
     * @return void
     */
    public function testExpandCriteriaWhenNoCompanyUser(): void
    {
        // Arrange
        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();

        // Act
        $resultTransfer = $this->expander->expandCriteria($sspAssetCriteriaTransfer);

        // Assert
        $this->assertSame($sspAssetCriteriaTransfer, $resultTransfer);
    }

    /**
     * @return void
     */
    public function testExpandCriteriaWhenNoPermissionsGranted(): void
    {
        // Arrange
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser(1);

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer);

        $this->expander
            ->expects($this->exactly(2))
            ->method('can')
            ->willReturnMap([
                [ViewBusinessUnitSspAssetPermissionPlugin::KEY, 1, false],
                [ViewCompanySspAssetPermissionPlugin::KEY, 1, false],
            ]);

        // Act
        $resultTransfer = $this->expander->expandCriteria($sspAssetCriteriaTransfer);

        // Assert
        $this->assertNotNull($resultTransfer->getSspAssetConditions());
        $this->assertNull($resultTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitCompanyId());
        $this->assertNull($resultTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitId());
    }
}
