<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;

class SspAssetCustomerPermissionExpander implements SspAssetCustomerPermissionExpanderInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCriteriaTransfer
     */
    public function expand(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCriteriaTransfer
    {
        $companyUserTransfer = $sspAssetCriteriaTransfer->getCompanyUser();

        if (!$companyUserTransfer) {
            return $sspAssetCriteriaTransfer;
        }

        if (!$sspAssetCriteriaTransfer->getSspAssetConditions()) {
            $sspAssetCriteriaTransfer->setSspAssetConditions(new SspAssetConditionsTransfer());
        }

        if ($this->can(ViewCompanySspAssetPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())) {
            $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->setAssignedBusinessUnitCompanyId($companyUserTransfer->getFkCompanyOrFail());

            return $sspAssetCriteriaTransfer;
        }

        if ($this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())) {
            $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->setAssignedBusinessUnitId($companyUserTransfer->getFkCompanyBusinessUnitOrFail());
        }

        return $sspAssetCriteriaTransfer;
    }
}
