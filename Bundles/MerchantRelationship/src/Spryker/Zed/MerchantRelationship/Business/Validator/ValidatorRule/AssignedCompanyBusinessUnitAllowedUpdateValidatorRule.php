<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;

class AssignedCompanyBusinessUnitAllowedUpdateValidatorRule extends AbstractAssignedCompanyBusinessUnitValidatorRule
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

        $ownerCompanyBusinessUnit = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail();
        $companyTransfer = $ownerCompanyBusinessUnit->getCompany()
            ? $ownerCompanyBusinessUnit->getCompanyOrFail()
            : $this->findCompanyTransferByMerchantRelationship($merchantRelationshipTransfer);

        if (!$companyTransfer) {
            $merchantRelationshipValidationErrorCollectionTransfer->addError(
                $this->createMerchantRelationshipErrorTransfer(
                    'assignedBusinessUnits',
                    sprintf('Company business units can not be assigned. Company not found.'),
                ),
            );

            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $notAllowedCompanyBusinessUnitIds = $this->defineNotAllowedCompanyBusinessUnitIds($companyTransfer, $companyBusinessUnitCollectionTransfer);

        return $this->addErrorsByNotAllowedCompanyBusinessUnitIds($merchantRelationshipValidationErrorCollectionTransfer, $notAllowedCompanyBusinessUnitIds);
    }
}
