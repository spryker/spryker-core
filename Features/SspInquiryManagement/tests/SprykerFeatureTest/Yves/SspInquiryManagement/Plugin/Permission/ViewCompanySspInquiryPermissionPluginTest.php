<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SspInquiry\Plugin\Permission;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewCompanySspInquiryPermissionPlugin;

class ViewCompanySspInquiryPermissionPluginTest extends Unit
{
    /**
     * @var \SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewCompanySspInquiryPermissionPlugin
     */
    protected ViewCompanySspInquiryPermissionPlugin $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->plugin = new ViewCompanySspInquiryPermissionPlugin();
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
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_COMPANY_USER => null,
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_SSP_INQUIRY => null,
        ];

        // Act
        $result = $this->plugin->can([], $context);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanReturnsTrueWhenUserCompanyMatchesSspInquiryCompany(): void
    {
        // Arrange
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setFkCompany(1);

         $sspInquiryTransfer = new SspInquiryTransfer();
         $sspInquiryTransfer->setCompanyUser($companyUserTransfer);

        $context = [
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_SSP_INQUIRY => $sspInquiryTransfer,
        ];

        // Act
        $result = $this->plugin->can([], $context);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanReturnsFalseWhenUserCompanyDoesNotMatchSspInquiryCompany(): void
    {
        // Arrange
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setFkCompany(1);

         $sspInquiryCompanyUserTransfer = new CompanyUserTransfer();
         $sspInquiryCompanyUserTransfer->setFkCompany(2);

         $sspInquiryTransfer = new SspInquiryTransfer();
         $sspInquiryTransfer->setCompanyUser($sspInquiryCompanyUserTransfer);

        $context = [
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_SSP_INQUIRY => $sspInquiryTransfer,
        ];

        // Act
        $result = $this->plugin->can([], $context);

        // Assert
        $this->assertFalse($result);
    }
}
