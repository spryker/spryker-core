<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface;

class MerchantRelationshipExpander implements MerchantRelationshipExpanderInterface
{
    /**
     * @var string
     */
    protected const FORMAT_NAME = '%s - %s';

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface
     */
    protected MerchantRelationshipRepositoryInterface $merchantRelationshipRepository;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface $merchantRelationshipRepository
     */
    public function __construct(MerchantRelationshipRepositoryInterface $merchantRelationshipRepository)
    {
        $this->merchantRelationshipRepository = $merchantRelationshipRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function expandWithName(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->setName($this->createMerchantRelationshipName($merchantRelationshipTransfer));

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function expandMerchantRelationshipCollection(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): MerchantRelationshipCollectionTransfer {
        $merchantRelationshipIds = $this->extractMerchantRelationshipIds($merchantRelationshipCollectionTransfer);
        $companyBusinessUnitTransfersGroupedByIdMerchantRelationship = $this->merchantRelationshipRepository
            ->getAssigneeCompanyBusinessUnitsGroupedByIdMerchantRelationship($merchantRelationshipIds);

        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $this->expandWithName($merchantRelationshipTransfer);
            $this->addAssigneeCompanyBusinessUnitRelationsToMerchantRelationshipTransfer(
                $merchantRelationshipTransfer,
                $companyBusinessUnitTransfersGroupedByIdMerchantRelationship,
            );
        }

        return $merchantRelationshipCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return string
     */
    protected function createMerchantRelationshipName(MerchantRelationshipTransfer $merchantRelationshipTransfer): string
    {
        return sprintf(
            static::FORMAT_NAME,
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
            $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()->getName(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param array<int, list<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer>> $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function addAssigneeCompanyBusinessUnitRelationsToMerchantRelationshipTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        array $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest
    ): MerchantRelationshipTransfer {
        $idMerchantRelationship = $merchantRelationshipTransfer->getIdMerchantRelationshipOrFail();
        $companyBusinessUnitTransfers = $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest[$idMerchantRelationship] ?? [];
        $companyBusinessUnitCollectionTransfer = (new CompanyBusinessUnitCollectionTransfer())
            ->setCompanyBusinessUnits(new ArrayObject($companyBusinessUnitTransfers));

        return $merchantRelationshipTransfer->setAssigneeCompanyBusinessUnits(
            $companyBusinessUnitCollectionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractMerchantRelationshipIds(MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer): array
    {
        $merchantRelationshipIds = [];
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $merchantRelationshipIds[] = $merchantRelationshipTransfer->getIdMerchantRelationshipOrFail();
        }

        return $merchantRelationshipIds;
    }
}
