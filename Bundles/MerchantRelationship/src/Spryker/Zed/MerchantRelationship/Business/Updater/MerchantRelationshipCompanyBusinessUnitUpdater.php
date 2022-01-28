<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Updater;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCompanyBusinessUnitMapperInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface;

class MerchantRelationshipCompanyBusinessUnitUpdater implements MerchantRelationshipCompanyBusinessUnitUpdaterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface
     */
    protected $merchantRelationshipRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface
     */
    protected $merchantRelationshipEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCompanyBusinessUnitMapperInterface
     */
    protected $merchantRelationshipCompanyBusinessUnitMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface $merchantRelationshipRepository
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager
     * @param \Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCompanyBusinessUnitMapperInterface $merchantRelationshipCompanyBusinessUnitMapper
     */
    public function __construct(
        MerchantRelationshipRepositoryInterface $merchantRelationshipRepository,
        MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager,
        MerchantRelationshipCompanyBusinessUnitMapperInterface $merchantRelationshipCompanyBusinessUnitMapper
    ) {
        $this->merchantRelationshipRepository = $merchantRelationshipRepository;
        $this->merchantRelationshipEntityManager = $merchantRelationshipEntityManager;
        $this->merchantRelationshipCompanyBusinessUnitMapper = $merchantRelationshipCompanyBusinessUnitMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function updateMerchantRelationshipCompanyBusinessUnitRelations(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $assignedCompanyBusinessUnitIds = $this->merchantRelationshipRepository
            ->getIdAssignedBusinessUnitsByMerchantRelationshipId($merchantRelationshipTransfer->getIdMerchantRelationshipOrFail());
        $requestedCompanyBusinessUnitIds = $this->getAssignedCompanyBusinessUnitIds($merchantRelationshipTransfer);

        $assignedCompanyBusinessUnitIdsToSave = array_diff($requestedCompanyBusinessUnitIds, $assignedCompanyBusinessUnitIds);
        $assignedCompanyBusinessUnitIdsToDelete = array_diff($assignedCompanyBusinessUnitIds, $requestedCompanyBusinessUnitIds);

        $addedCompanyBusinessUnitCollectionTransfer = $this->merchantRelationshipEntityManager->addAssignedCompanyBusinessUnits(
            $assignedCompanyBusinessUnitIdsToSave,
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
        );

        if ($addedCompanyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()->count()) {
            $this->merchantRelationshipCompanyBusinessUnitMapper->mapCompanyBusinessUnitCollectionTransferToMerchantRelationshipTransfer(
                $addedCompanyBusinessUnitCollectionTransfer,
                $merchantRelationshipTransfer,
            );
        }

        $this->merchantRelationshipEntityManager->removeAssignedCompanyBusinessUnits(
            $assignedCompanyBusinessUnitIdsToDelete,
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
        );

        if ($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits() === null) {
            $merchantRelationshipTransfer->setAssigneeCompanyBusinessUnits(
                new CompanyBusinessUnitCollectionTransfer(),
            );
        }

        return $merchantRelationshipTransfer;
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
