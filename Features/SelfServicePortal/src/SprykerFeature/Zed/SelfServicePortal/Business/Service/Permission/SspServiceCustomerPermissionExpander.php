<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Permission;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class SspServiceCustomerPermissionExpander implements SspServiceCustomerPermissionExpanderInterface
{
    use PermissionAwareTrait;

    public function __construct(
        protected CompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        protected CompanyFacadeInterface $companyFacade
    ) {
    }

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
            $serviceConditionsTransfer->setCompanyUuid($this->getCompanyUuid($companyUserTransfer));

            return $sspServiceCriteriaTransfer;
        }

        if ($this->can('SeeBusinessUnitOrdersPermissionPlugin', $idCompanyUser)) {
            $serviceConditionsTransfer->setCompanyBusinessUnitUuid($this->getCompanyBusinessUnitUuid($companyUserTransfer));

            return $sspServiceCriteriaTransfer;
        }

        $serviceConditionsTransfer->setCustomerReference($companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());

        return $sspServiceCriteriaTransfer;
    }

    protected function getCompanyBusinessUnitUuid(CompanyUserTransfer $companyUserTransfer): string
    {
        if ($companyUserTransfer->getCompanyBusinessUnitOrFail()->getUuid()) {
            return $companyUserTransfer->getCompanyBusinessUnitOrFail()->getUuid();
        }

        return $this->companyBusinessUnitFacade->getCompanyBusinessUnitById($companyUserTransfer->getCompanyBusinessUnitOrFail())->getUuidOrFail();
    }

    protected function getCompanyUuid(CompanyUserTransfer $companyUserTransfer): string
    {
        if ($companyUserTransfer->getCompanyOrFail()->getUuid()) {
            return $companyUserTransfer->getCompanyOrFail()->getUuid();
        }

        return $this->companyFacade->getCompanyById($companyUserTransfer->getCompanyOrFail())->getUuidOrFail();
    }
}
