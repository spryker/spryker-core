<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Model;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface;

class MerchantRelationshipWriter implements MerchantRelationshipWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface
     */
    protected $merchantRelationshipReader;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface $merchantRelationshipReader
     */
    public function __construct(
        MerchantRelationshipEntityManagerInterface $entityManager,
        MerchantRelationshipReaderInterface $merchantRelationshipReader
    ) {
        $this->entityManager = $entityManager;
        $this->merchantRelationshipReader = $merchantRelationshipReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function create(MerchantRelationshipTransfer $merchantRelationTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationTransfer->requireFkMerchant()
            ->requireFkCompanyBusinessUnit();

        $merchantRelationTransfer = $this->entityManager->saveMerchantRelationship($merchantRelationTransfer);
        $this->saveAssignedCompanyBusinessUnits($merchantRelationTransfer);

        return $merchantRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function update(MerchantRelationshipTransfer $merchantRelationTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationTransfer->requireIdMerchantRelationship()
            ->requireFkMerchant()
            ->requireFkCompanyBusinessUnit();

        $merchantRelationTransfer = $this->entityManager->saveMerchantRelationship($merchantRelationTransfer);
        $this->saveAssignedCompanyBusinessUnits($merchantRelationTransfer);

        return $merchantRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationTransfer
     *
     * @return void
     */
    public function delete(MerchantRelationshipTransfer $merchantRelationTransfer): void
    {
        $merchantRelationTransfer->requireIdMerchantRelationship();

        $this->entityManager->deleteMerchantRelationshipById($merchantRelationTransfer->getIdMerchantRelationship());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationTransfer
     *
     * @return void
     */
    protected function saveAssignedCompanyBusinessUnits(MerchantRelationshipTransfer $merchantRelationTransfer): void
    {
        $currentIdAssignedCompanyBusinessUnits = $this->merchantRelationshipReader
            ->getIdAssignedBusinessUnitsByMerchantRelationshipId($merchantRelationTransfer->getIdMerchantRelationship());
        $requestedIdAssignedCompanyBusinessUnits = $this->findIdAssignedCompanyBusinessUnits($merchantRelationTransfer);

        $saveIdAssignedCompanyBusinessUnits = array_diff($requestedIdAssignedCompanyBusinessUnits, $currentIdAssignedCompanyBusinessUnits);
        $deleteIdAssignedCompanyBusinessUnits = array_diff($currentIdAssignedCompanyBusinessUnits, $requestedIdAssignedCompanyBusinessUnits);

        $this->entityManager->addAssignedCompanyBusinessUnits(
            $saveIdAssignedCompanyBusinessUnits,
            $merchantRelationTransfer->getIdMerchantRelationship()
        );
        $this->entityManager->removeAssignedCompanyBusinessUnits(
            $deleteIdAssignedCompanyBusinessUnits,
            $merchantRelationTransfer->getIdMerchantRelationship()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationTransfer
     *
     * @return int[]
     */
    protected function findIdAssignedCompanyBusinessUnits($merchantRelationTransfer): array
    {
        if (!$merchantRelationTransfer->getAssigneeCompanyBusinessUnits()) {
            return [];
        }

        $companyBusinessUnits = $merchantRelationTransfer->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits();
        if (!$companyBusinessUnits) {
            return [];
        }

        $ids = [];
        foreach ($companyBusinessUnits as $companyBusinessUnit) {
            $ids[] = $companyBusinessUnit->getIdCompanyBusinessUnit();
        }

        return $ids;
    }
}
