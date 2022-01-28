<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface;

class OwnerCompanyBusinessUnitAllowedValidatorRule extends AbstractMerchantRelationshipValidatorRule
{
    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface $merchantRelationshipRepository
     * @param \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(
        MerchantRelationshipRepositoryInterface $merchantRelationshipRepository,
        MerchantRelationshipToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
    ) {
        $this->merchantRelationshipRepository = $merchantRelationshipRepository;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

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
        if ($merchantRelationshipTransfer->getIdMerchantRelationship() === null) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $requestedOwnerCompanyBusinessUnitTransfer = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit();
        if (!$requestedOwnerCompanyBusinessUnitTransfer || !$requestedOwnerCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit()) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $existingMerchantRelationshipTransfer = $this->findMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationshipOrFail());

        if (!$existingMerchantRelationshipTransfer) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $existingOwnerCompanyBusinessUnit = $existingMerchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail();
        if ($this->isSameCompanyBusinessUnit($existingOwnerCompanyBusinessUnit, $requestedOwnerCompanyBusinessUnitTransfer)) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        if (
            $this->isRequestedOwnerCompanyBusinessUnitAssignedToSameCompanyAsExisting(
                $existingOwnerCompanyBusinessUnit,
                $requestedOwnerCompanyBusinessUnitTransfer,
            )
        ) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $merchantRelationshipErrorTransfer = $this->createMerchantRelationshipErrorTransfer(
            'idBusinessUnitOwner',
            sprintf('Can not find related company business unit by id "%s".', $requestedOwnerCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit()),
        );

        return $merchantRelationshipValidationErrorCollectionTransfer->addError($merchantRelationshipErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $existingOwnerCompanyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $requestedOwnerCompanyBusinessUnitTransfer
     *
     * @return bool
     */
    protected function isRequestedOwnerCompanyBusinessUnitAssignedToSameCompanyAsExisting(
        CompanyBusinessUnitTransfer $existingOwnerCompanyBusinessUnitTransfer,
        CompanyBusinessUnitTransfer $requestedOwnerCompanyBusinessUnitTransfer
    ): bool {
        $companyBusinessUnitCollectionTransfer = $this->getCompanyBusinessUnitCollection(
            $existingOwnerCompanyBusinessUnitTransfer->getCompanyOrFail()->getIdCompanyOrFail(),
        );

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            if ($this->isSameCompanyBusinessUnit($companyBusinessUnitTransfer, $requestedOwnerCompanyBusinessUnitTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $requestedCompanyBusinessUnitTransfer
     *
     * @return bool
     */
    protected function isSameCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        CompanyBusinessUnitTransfer $requestedCompanyBusinessUnitTransfer
    ): bool {
        return $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail() === $requestedCompanyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
    }
}
