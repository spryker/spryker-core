<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

class MerchantRelationshipCompanyBusinessUnitMapper implements MerchantRelationshipCompanyBusinessUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $addedCompanyBusinessUnitCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function mapCompanyBusinessUnitCollectionTransferToMerchantRelationshipTransfer(
        CompanyBusinessUnitCollectionTransfer $addedCompanyBusinessUnitCollectionTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $companyBusinessUnitCollectionTransfer = $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits();

        if (!$companyBusinessUnitCollectionTransfer) {
            return $merchantRelationshipTransfer->setAssigneeCompanyBusinessUnits($addedCompanyBusinessUnitCollectionTransfer);
        }

        foreach ($addedCompanyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $addedCompanyBusinessUnitTransfer) {
            $companyBusinessUnitCollectionTransfer = $this->mapCompanyBusinessUnitTransferToCompanyBusinessUnitCollectionTransfer(
                $addedCompanyBusinessUnitTransfer,
                $companyBusinessUnitCollectionTransfer,
            );
        }

        return $merchantRelationshipTransfer->setAssigneeCompanyBusinessUnits($companyBusinessUnitCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $addedCompanyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function mapCompanyBusinessUnitTransferToCompanyBusinessUnitCollectionTransfer(
        CompanyBusinessUnitTransfer $addedCompanyBusinessUnitTransfer,
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            if (!$this->isSameCompanyBusinessUnit($addedCompanyBusinessUnitTransfer, $companyBusinessUnitTransfer)) {
                continue;
            }

            $companyBusinessUnitTransfer->fromArray($addedCompanyBusinessUnitTransfer->toArray());
        }

        return $companyBusinessUnitCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $addedCompanyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    protected function isSameCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $addedCompanyBusinessUnitTransfer,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): bool {
        return $addedCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit() === $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
    }
}
