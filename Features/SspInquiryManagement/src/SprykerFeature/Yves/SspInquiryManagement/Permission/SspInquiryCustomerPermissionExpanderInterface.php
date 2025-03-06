<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Permission;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;

interface SspInquiryCustomerPermissionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryConditionsTransfer $sspInquiryConditionsTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryConditionsTransfer
     */
    public function extendSspInquiryCriteriaTransferWithPermissions(
        SspInquiryConditionsTransfer $sspInquiryConditionsTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): SspInquiryConditionsTransfer;
}
