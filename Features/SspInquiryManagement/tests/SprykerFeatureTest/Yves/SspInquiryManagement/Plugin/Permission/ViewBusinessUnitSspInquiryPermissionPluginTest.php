<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SspInquiry\Plugin\Permission;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewBusinessUnitSspInquiryPermissionPlugin;

class ViewBusinessUnitSspInquiryPermissionPluginTest extends Unit
{
    /**
     * @var \SprykerFeature\Yves\SspInquiryManagement\Plugin\Permission\ViewBusinessUnitSspInquiryPermissionPlugin
     */
    protected ViewBusinessUnitSspInquiryPermissionPlugin $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->plugin = new ViewBusinessUnitSspInquiryPermissionPlugin();
    }

    /**
     * @return void
     */
    public function testCanReturnsTrueWhenContextIsEmpty(): void
    {
        // Arrange
        $context = [];

        // Act
        $result = $this->plugin->can([], $context);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanReturnsTrueWhenCompanyUserAndSspInquiryAreNotSet(): void
    {
        // Arrange
        $context = [
            ViewBusinessUnitSspInquiryPermissionPlugin::CONTEXT_COMPANY_USER => null,
            ViewBusinessUnitSspInquiryPermissionPlugin::CONTEXT_SSP_INQUIRY => null,
        ];

        // Act
        $result = $this->plugin->can([], $context);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanReturnsTrueWhenCompanyUserBusinessUnitMatchesSspInquiryBusinessUnit(): void
    {
        // Arrange
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setFkCompanyBusinessUnit(1);

         $sspInquiryTransfer = new SspInquiryTransfer();
         $sspInquiryTransfer->setCompanyUser($companyUserTransfer);

        $context = [
            ViewBusinessUnitSspInquiryPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewBusinessUnitSspInquiryPermissionPlugin::CONTEXT_SSP_INQUIRY => $sspInquiryTransfer,
        ];

        // Act
        $result = $this->plugin->can([], $context);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanReturnsFalseWhenCompanyUserBusinessUnitDoesNotMatchSspInquiryBusinessUnit(): void
    {
        // Arrange
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setFkCompanyBusinessUnit(1);

         $sspInquiryCompanyUserTransfer = new CompanyUserTransfer();
         $sspInquiryCompanyUserTransfer->setFkCompanyBusinessUnit(2);

         $sspInquiryTransfer = new SspInquiryTransfer();
         $sspInquiryTransfer->setCompanyUser($sspInquiryCompanyUserTransfer);

        $context = [
            ViewBusinessUnitSspInquiryPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewBusinessUnitSspInquiryPermissionPlugin::CONTEXT_SSP_INQUIRY => $sspInquiryTransfer,
        ];

        // Act
        $result = $this->plugin->can([], $context);

        // Assert
        $this->assertFalse($result);
    }
}
