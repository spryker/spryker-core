<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Company\Validator;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;

class CompanyBusinessUnitValidator implements CompanyBusinessUnitValidatorInterface
{
    public function __construct(protected CompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade)
    {
    }

    public function isCompanyBusinessUnitBelongsToCompany(CompanyUserTransfer $companyUserTransfer, int $idCompanyBusinessUnit): bool
    {
        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getFkCompanyOrFail()),
        );

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            if ($companyBusinessUnitTransfer->getIdCompanyBusinessUnit() === $idCompanyBusinessUnit) {
                return true;
            }
        }

        return false;
    }

    public function isCompanyBusinessUnitUuidBelongsToCompany(CompanyUserTransfer $companyUserTransfer, string $companyBusinessUnitUuid): bool
    {
        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getFkCompanyOrFail()),
        );

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            if ($companyBusinessUnitTransfer->getUuid() === $companyBusinessUnitUuid) {
                return true;
            }
        }

        return false;
    }
}
