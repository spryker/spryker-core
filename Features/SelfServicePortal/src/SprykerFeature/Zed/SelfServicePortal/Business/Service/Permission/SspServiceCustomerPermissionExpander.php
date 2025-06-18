<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Permission;

use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class SspServiceCustomerPermissionExpander implements SspServiceCustomerPermissionExpanderInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
    public function expand(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCriteriaTransfer
    {
        $companyUserTransfer = $sspServiceCriteriaTransfer->getCompanyUserOrFail();

        if (!$sspServiceCriteriaTransfer->getServiceConditions()) {
            $sspServiceCriteriaTransfer->setServiceConditions(new SspServiceConditionsTransfer());
        }

        $serviceConditionsTransfer = $sspServiceCriteriaTransfer->getServiceConditionsOrFail();
        $idCompanyUser = $companyUserTransfer->getIdCompanyUserOrFail();

        $serviceConditionsTransfer->setCompanyUuid(null);
        $serviceConditionsTransfer->setCompanyBusinessUnitUuid(null);

        if ($this->can('SeeCompanyOrdersPermissionPlugin', $idCompanyUser)) {
            $serviceConditionsTransfer->setCompanyUuid($companyUserTransfer->getCompanyOrFail()->getUuidOrFail());

            return $sspServiceCriteriaTransfer;
        }

        if ($this->can('SeeBusinessUnitOrdersPermissionPlugin', $idCompanyUser)) {
            $serviceConditionsTransfer->setCompanyBusinessUnitUuid(
                $companyUserTransfer->getCompanyBusinessUnitOrFail()->getUuidOrFail(),
            );

            return $sspServiceCriteriaTransfer;
        }

        $serviceConditionsTransfer->setCustomerReference($companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());

        return $sspServiceCriteriaTransfer;
    }
}
