<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Company\Validator;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyBusinessUnitValidatorInterface
{
    public function isCompanyBusinessUnitBelongsToCompany(CompanyUserTransfer $companyUserTransfer, int $idCompanyBusinessUnit): bool;

    public function isCompanyBusinessUnitUuidBelongsToCompany(CompanyUserTransfer $companyUserTransfer, string $companyBusinessUnitUuid): bool;
}
