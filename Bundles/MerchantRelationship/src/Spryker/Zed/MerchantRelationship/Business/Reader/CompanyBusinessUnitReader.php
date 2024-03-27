<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Reader;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface
     */
    protected MerchantRelationshipToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(MerchantRelationshipToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade)
    {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /**
     * @param list<int> $companyBusinessUnitIds
     *
     * @return array<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer>
     */
    public function getCompanyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit(array $companyBusinessUnitIds): array
    {
        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setWithoutExpanders(true)
            ->setCompanyBusinessUnitIds($companyBusinessUnitIds);

        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
        if ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()->count() === 0) {
            return [];
        }

        return $this->getCompanyBusinessUnitsIndexedById(
            $companyBusinessUnitCollectionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer>
     */
    protected function getCompanyBusinessUnitsIndexedById(
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): array {
        $indexedCompanyBusinessUnitTransfers = [];
        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            if ($companyBusinessUnitTransfer->getEmail() === null) {
                continue;
            }

            $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            $indexedCompanyBusinessUnitTransfers[$idCompanyBusinessUnit] = $companyBusinessUnitTransfer;
        }

        return $indexedCompanyBusinessUnitTransfers;
    }
}
