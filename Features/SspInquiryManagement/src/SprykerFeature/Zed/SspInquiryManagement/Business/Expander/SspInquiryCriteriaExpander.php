<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Expander;

use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewBusinessUnitSspInquiryPermissionPlugin;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewCompanySspInquiryPermissionPlugin;

class SspInquiryCriteriaExpander implements SspInquiryCriteriaExpanderInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCriteriaTransfer
     */
    public function expandCriteriaBasedOnCompanyUserPermissions(
        SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
    ): SspInquiryCriteriaTransfer {
        $sspInquiryConditionsTransfer = $sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail();

        $companyUserTransfer = $sspInquiryConditionsTransfer->getSspInquiryOwnerConditionGroup()?->getCompanyUser();

        if (!$companyUserTransfer) {
            return $sspInquiryCriteriaTransfer;
        }

        $sspInquiryConditionsTransfer->getSspInquiryOwnerConditionGroupOrFail()->setIdCompany(null);

        if ($this->can(ViewCompanySspInquiryPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())) {
            $sspInquiryConditionsTransfer->getSspInquiryOwnerConditionGroupOrFail()->setIdCompany($companyUserTransfer->getFkCompany());

            return $sspInquiryCriteriaTransfer->setSspInquiryConditions($sspInquiryConditionsTransfer);
        }

        $sspInquiryConditionsTransfer->getSspInquiryOwnerConditionGroupOrFail()->setIdCompanyBusinessUnit(null);

        if ($this->can(ViewBusinessUnitSspInquiryPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())) {
            $sspInquiryConditionsTransfer->getSspInquiryOwnerConditionGroupOrFail()->setIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnitOrFail());
        }

        return $sspInquiryCriteriaTransfer->setSspInquiryConditions($sspInquiryConditionsTransfer);
    }
}
