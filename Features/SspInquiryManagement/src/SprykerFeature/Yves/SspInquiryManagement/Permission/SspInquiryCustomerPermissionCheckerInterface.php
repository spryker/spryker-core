<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Permission;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;

interface SspInquiryCustomerPermissionCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function canViewSspInquiry(SspInquiryTransfer $sspInquiryTransfer, CompanyUserTransfer $companyUserTransfer): bool;
}
