<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;

class AssignedCompanyBusinessUnitAllowedCreateValidatorRule extends AbstractAssignedCompanyBusinessUnitValidatorRule
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer
     */
    public function validate(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
    ): MerchantRelationshipValidationErrorCollectionTransfer {
        $companyBusinessUnitCollectionTransfer = $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits();
        if (!$companyBusinessUnitCollectionTransfer || $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()->count() === 0) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $ownerCompanyBusinessUnit = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit();
        if (!$ownerCompanyBusinessUnit) {
            $ownerCompanyBusinessUnit = $this->companyBusinessUnitFacade->findCompanyBusinessUnitById($merchantRelationshipTransfer->getFkCompanyBusinessUnitOrFail());
        }

        $companyTransfer = $ownerCompanyBusinessUnit->getCompany()
            ? $ownerCompanyBusinessUnit->getCompanyOrFail()
            : $this->findCompanyTransferByCompanyBusinessUnitId($ownerCompanyBusinessUnit->getIdCompanyBusinessUnitOrFail());

        if (!$companyTransfer) {
            $merchantRelationshipErrorTransfer = $this->createMerchantRelationshipErrorTransfer(
                'idBusinessUnitOwner',
                sprintf('Company not found for company business unit id "%s"', $merchantRelationshipTransfer->getFkCompanyBusinessUnitOrFail()),
            );

            return $merchantRelationshipValidationErrorCollectionTransfer->addError($merchantRelationshipErrorTransfer);
        }

        $notAllowedCompanyBusinessUnitIds = $this->defineNotAllowedCompanyBusinessUnitIds($companyTransfer, $companyBusinessUnitCollectionTransfer);
        if (!$notAllowedCompanyBusinessUnitIds) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        return $this->addErrorsByNotAllowedCompanyBusinessUnitIds($merchantRelationshipValidationErrorCollectionTransfer, $notAllowedCompanyBusinessUnitIds);
    }
}
