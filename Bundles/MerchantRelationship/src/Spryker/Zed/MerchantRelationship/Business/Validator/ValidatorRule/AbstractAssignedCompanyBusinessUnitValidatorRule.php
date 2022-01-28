<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface;

abstract class AbstractAssignedCompanyBusinessUnitValidatorRule extends AbstractMerchantRelationshipValidatorRule
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
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    protected function findCompanyTransferByMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): ?CompanyTransfer {
        if (!$merchantRelationshipTransfer->getIdMerchantRelationship()) {
            return null;
        }

        $existingMerchantRelationshipTransfer = $this->findMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationshipOrFail());
        if (!$existingMerchantRelationshipTransfer || !$existingMerchantRelationshipTransfer->getOwnerCompanyBusinessUnit()) {
            return null;
        }

        return $existingMerchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getCompany();
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    protected function findCompanyTransferByCompanyBusinessUnitId(int $idCompanyBusinessUnit): ?CompanyTransfer
    {
        $companyBusinessUnitTransfer = $this->companyBusinessUnitFacade->findCompanyBusinessUnitById($idCompanyBusinessUnit);
        if (!$companyBusinessUnitTransfer) {
            return null;
        }

        return $companyBusinessUnitTransfer->getCompany();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return array<int>
     */
    protected function defineNotAllowedCompanyBusinessUnitIds(
        CompanyTransfer $companyTransfer,
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): array {
        $allowedCompanyBusinessUnitCollectionTransfer = $this->getCompanyBusinessUnitCollection($companyTransfer->getIdCompanyOrFail());

        $allowedCompanyBusinessUnitIds = $this->extractCompanyBusinessUnitIdsFromCompanyBusinessUnitCollection($allowedCompanyBusinessUnitCollectionTransfer);
        $requestedCompanyBusinessUnitIds = $this->extractCompanyBusinessUnitIdsFromCompanyBusinessUnitCollection($companyBusinessUnitCollectionTransfer);

        return array_diff($requestedCompanyBusinessUnitIds, $allowedCompanyBusinessUnitIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return array<int>
     */
    protected function extractCompanyBusinessUnitIdsFromCompanyBusinessUnitCollection(
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): array {
        $companyBusinessUnitIds = [];
        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return $companyBusinessUnitIds;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
     * @param array<int> $notAllowedCompanyBusinessUnitIds
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer
     */
    protected function addErrorsByNotAllowedCompanyBusinessUnitIds(
        MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer,
        array $notAllowedCompanyBusinessUnitIds
    ): MerchantRelationshipValidationErrorCollectionTransfer {
        foreach ($notAllowedCompanyBusinessUnitIds as $notAllowedIdCompanyBusinessUnit) {
            $merchantRelationshipValidationErrorCollectionTransfer->addError(
                $this->createMerchantRelationshipErrorTransfer(
                    'assignedBusinessUnits',
                    sprintf('Company business units can not be assigned by id "%s".', $notAllowedIdCompanyBusinessUnit),
                ),
            );
        }

        return $merchantRelationshipValidationErrorCollectionTransfer;
    }
}
