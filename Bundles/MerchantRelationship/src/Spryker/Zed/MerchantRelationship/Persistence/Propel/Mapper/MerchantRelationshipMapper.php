<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Util\PropelModelPager;

class MerchantRelationshipMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship> $merchantRelationshipEntities
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     * @param \Propel\Runtime\Util\PropelModelPager $propelModelPager
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function mapMerchantRelationshipEntitiesToMerchantRelationshipCollectionTransfer(
        Collection $merchantRelationshipEntities,
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer,
        PropelModelPager $propelModelPager
    ): MerchantRelationshipCollectionTransfer {
        $paginationTransfer = $this->mapPaginationModelToPaginationTransfer(
            $propelModelPager,
            new PaginationTransfer(),
        );

        $merchantRelationshipTransfers = $this->mapMerchantRelationshipEntitiesToMerchantRelationshipTransfers(
            $merchantRelationshipEntities,
            [],
        );

        return $merchantRelationshipCollectionTransfer
            ->setPagination($paginationTransfer)
            ->setMerchantRelationships(new ArrayObject($merchantRelationshipTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship $spyMerchantRelationship
     *
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship
     */
    public function mapMerchantRelationshipTransferToEntity(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        SpyMerchantRelationship $spyMerchantRelationship
    ): SpyMerchantRelationship {
        $spyMerchantRelationship->fromArray(
            $merchantRelationshipTransfer->modifiedToArray(false),
        );

        return $spyMerchantRelationship;
    }

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship $spyMerchantRelationship
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function mapEntityToMerchantRelationshipTransfer(
        SpyMerchantRelationship $spyMerchantRelationship,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $merchantRelationshipTransfer->fromArray(
            $spyMerchantRelationship->toArray(),
            true,
        );

        /** @var \Orm\Zed\Merchant\Persistence\SpyMerchant|null $spyMerchant */
        $spyMerchant = $spyMerchantRelationship->getMerchant();
        if ($spyMerchant) {
            $merchantTransfer = $merchantRelationshipTransfer->getMerchant() ?: new MerchantTransfer();
            $merchantTransfer->fromArray($spyMerchantRelationship->getMerchant()->toArray(), true);
            $merchantRelationshipTransfer->setMerchant($merchantTransfer);
        }

        $merchantRelationshipTransfer->setOwnerCompanyBusinessUnit(
            $this->mapCompanyBusinessUnitEntityToTransfer(
                $spyMerchantRelationship->getCompanyBusinessUnit(),
                new CompanyBusinessUnitTransfer(),
            ),
        );

        return $this->mapAssigneeCompanyBusinessUnits($spyMerchantRelationship, $merchantRelationshipTransfer);
    }

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship $spyMerchantRelationship
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function mapMerchantRelationshipEntityToMerchantRelationshipTransfer(
        SpyMerchantRelationship $spyMerchantRelationship,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        return $merchantRelationshipTransfer->fromArray($spyMerchantRelationship->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship> $merchantRelationshipEntities
     * @param array<\Generated\Shared\Transfer\MerchantRelationshipTransfer> $merchantRelationshipTransfers
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    protected function mapMerchantRelationshipEntitiesToMerchantRelationshipTransfers(
        Collection $merchantRelationshipEntities,
        array $merchantRelationshipTransfers
    ): array {
        foreach ($merchantRelationshipEntities as $merchantRelationshipEntity) {
            $merchantRelationshipTransfers[] = $this->mapEntityToMerchantRelationshipTransfer(
                $merchantRelationshipEntity,
                new MerchantRelationshipTransfer(),
            );
        }

        return $merchantRelationshipTransfers;
    }

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $spyCompanyBusinessUnit
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function mapCompanyBusinessUnitEntityToTransfer(
        SpyCompanyBusinessUnit $spyCompanyBusinessUnit,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        $companyTransfer = (new CompanyTransfer())->fromArray(
            $spyCompanyBusinessUnit->getCompany()->toArray(),
            true,
        );

        $companyBusinessUnitTransfer
            ->fromArray($spyCompanyBusinessUnit->toArray(), true)
            ->setCompany($companyTransfer);

        return $companyBusinessUnitTransfer;
    }

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship $spyMerchantRelationship
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function mapAssigneeCompanyBusinessUnits(
        SpyMerchantRelationship $spyMerchantRelationship,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $merchantRelationshipTransfer->setAssigneeCompanyBusinessUnits(new CompanyBusinessUnitCollectionTransfer());
        foreach ($spyMerchantRelationship->getSpyMerchantRelationshipToCompanyBusinessUnits() as $spyMerchantRelationshipToCompanyBusinessUnits) {
            $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()
                ->addCompanyBusinessUnit(
                    $this->mapCompanyBusinessUnitEntityToTransfer(
                        $spyMerchantRelationshipToCompanyBusinessUnits->getCompanyBusinessUnit(),
                        new CompanyBusinessUnitTransfer(),
                    ),
                );
        }

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Propel\Runtime\Util\PropelModelPager $propelModelPager
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function mapPaginationModelToPaginationTransfer(
        PropelModelPager $propelModelPager,
        PaginationTransfer $paginationTransfer
    ): PaginationTransfer {
        return $paginationTransfer
            ->setPage($propelModelPager->getPage())
            ->setMaxPerPage($propelModelPager->getMaxPerPage())
            ->setNbResults($propelModelPager->getNbResults())
            ->setFirstIndex($propelModelPager->getFirstIndex())
            ->setLastIndex($propelModelPager->getLastIndex())
            ->setFirstPage($propelModelPager->getFirstPage())
            ->setLastPage($propelModelPager->getLastPage())
            ->setNextPage($propelModelPager->getNextPage())
            ->setPreviousPage($propelModelPager->getPreviousPage());
    }
}
