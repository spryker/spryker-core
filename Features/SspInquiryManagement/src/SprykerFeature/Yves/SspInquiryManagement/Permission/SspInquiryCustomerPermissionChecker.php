<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Permission;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewBusinessUnitSspInquiryPermissionPlugin;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewCompanySspInquiryPermissionPlugin;

class SspInquiryCustomerPermissionChecker implements SspInquiryCustomerPermissionCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function canViewSspInquiry(SspInquiryTransfer $sspInquiryTransfer, CompanyUserTransfer $companyUserTransfer): bool
    {
        $isSspInquiryOwner = $sspInquiryTransfer->getCompanyUserOrFail()->getIdCompanyUser() === $companyUserTransfer->getIdCompanyUser();

        $canViewCompanySspInquiries = $this->can(ViewCompanySspInquiryPermissionPlugin::KEY, [
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_SSP_INQUIRY => $sspInquiryTransfer,
        ]);

        $canViewBusinessUnitSspInquiries = $this->can(ViewBusinessUnitSspInquiryPermissionPlugin::KEY, [
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewCompanySspInquiryPermissionPlugin::CONTEXT_SSP_INQUIRY => $sspInquiryTransfer,
        ]);

        return $isSspInquiryOwner || $canViewCompanySspInquiries || $canViewBusinessUnitSspInquiries;
    }
}
