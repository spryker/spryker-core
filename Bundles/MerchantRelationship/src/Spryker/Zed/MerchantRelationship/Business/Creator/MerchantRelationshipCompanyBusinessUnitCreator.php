<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Creator;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface;

class MerchantRelationshipCompanyBusinessUnitCreator implements MerchantRelationshipCompanyBusinessUnitCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface
     */
    protected $merchantRelationshipEntityManager;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager
     */
    public function __construct(MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager)
    {
        $this->merchantRelationshipEntityManager = $merchantRelationshipEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationshipCompanyBusinessUnitRelations(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $assignedCompanyBusinessUnitIds = $this->getAssignedCompanyBusinessUnitIds($merchantRelationshipTransfer);
        if (!$assignedCompanyBusinessUnitIds) {
            $merchantRelationshipTransfer->setAssigneeCompanyBusinessUnits(
                new CompanyBusinessUnitCollectionTransfer(),
            );

            return $merchantRelationshipTransfer;
        }

        $assignedCompanyBusinessUnitCollectionTransfer = $this->merchantRelationshipEntityManager->addAssignedCompanyBusinessUnits(
            $assignedCompanyBusinessUnitIds,
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
        );

        return $merchantRelationshipTransfer->setAssigneeCompanyBusinessUnits($assignedCompanyBusinessUnitCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return array<int>
     */
    protected function getAssignedCompanyBusinessUnitIds(MerchantRelationshipTransfer $merchantRelationshipTransfer): array
    {
        if (!$this->hasCompanyBusinessUnitsAssigned($merchantRelationshipTransfer)) {
            return [];
        }

        $companyBusinessUnitIds = [];
        foreach ($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return array_unique($companyBusinessUnitIds);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return bool
     */
    protected function hasCompanyBusinessUnitsAssigned(MerchantRelationshipTransfer $merchantRelationshipTransfer): bool
    {
        return $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()
            && $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail()->getCompanyBusinessUnits()->count();
    }
}
