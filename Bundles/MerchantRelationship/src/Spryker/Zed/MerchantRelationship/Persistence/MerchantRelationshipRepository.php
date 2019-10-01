<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipPersistenceFactory getFactory()
 */
class MerchantRelationshipRepository extends AbstractRepository implements MerchantRelationshipRepositoryInterface
{
    protected const COL_MAX_ID = 'MAX_ID';

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function getMerchantRelationshipById(int $idMerchantRelationship): ?MerchantRelationshipTransfer
    {
        /** @var \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship|null $spyMerchantRelation */
        $spyMerchantRelation = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->filterByIdMerchantRelationship($idMerchantRelationship)
            ->findOne();

        if (!$spyMerchantRelation) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantRelationshipMapper()
            ->mapEntityToMerchantRelationshipTransfer($spyMerchantRelation, new MerchantRelationshipTransfer());
    }

    /**
     * @param string $merchantRelationshipKey
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipByKey(string $merchantRelationshipKey): ?MerchantRelationshipTransfer
    {
        $spyMerchantRelation = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->filterByMerchantRelationshipKey($merchantRelationshipKey)
            ->findOne();

        if (!$spyMerchantRelation) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantRelationshipMapper()
            ->mapEntityToMerchantRelationshipTransfer($spyMerchantRelation, new MerchantRelationshipTransfer());
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return int[]
     */
    public function getIdAssignedBusinessUnitsByMerchantRelationshipId(int $idMerchantRelationship): array
    {
        return $this->getFactory()
            ->createMerchantRelationshipToCompanyBusinessUnitQuery()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->select([SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT])
            ->find()
            ->toArray();
    }

    /**
     * @param string $candidate
     *
     * @return bool
     */
    public function hasKey(string $candidate): bool
    {
        return $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->filterByMerchantRelationshipKey($candidate)
            ->exists();
    }

    /**
     * @return int
     */
    public function getMaxMerchantRelationshipId(): int
    {
        return (int)$this->getFactory()
            ->createMerchantRelationshipQuery()
            ->withColumn(
                sprintf('MAX(%s)', SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP),
                static::COL_MAX_ID
            )
            ->select([
                static::COL_MAX_ID,
            ])
            ->findOne();
    }

    /**
     * @module Merchant
     * @module CompanyBusinessUnit
     *
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getAssignedMerchantRelationshipsByIdCompanyBusinessUnit(int $idCompanyBusinessUnit): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship[] $merchantRelationshipEntities */
        $merchantRelationshipEntities = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->joinWithCompanyBusinessUnit()
            ->joinWithMerchant()
            ->useSpyMerchantRelationshipToCompanyBusinessUnitQuery()
                ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->endUse()
            ->find();

        if ($merchantRelationshipEntities->isEmpty()) {
            return [];
        }

        $merchantRelationshipCollection = [];

        foreach ($merchantRelationshipEntities as $merchantRelationshipEntity) {
            $merchantRelationshipCollection[] = $this->getFactory()
                ->createPropelMerchantRelationshipMapper()
                ->mapEntityToMerchantRelationshipTransfer($merchantRelationshipEntity, new MerchantRelationshipTransfer());
        }

        return $merchantRelationshipCollection;
    }

    /**
     * @module CompanyBusinessUnit
     * @module Merchant
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollection(MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer): array
    {
        $merchantRelationshipQuery = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->leftJoinCompanyBusinessUnit()
            ->innerJoinWithMerchant();

        $merchantRelationshipQuery = $this->addFiltersToMerchantRelationshipQuery(
            $merchantRelationshipQuery,
            $merchantRelationshipFilterTransfer
        );

        $merchantRelationEntities = $merchantRelationshipQuery->find();

        $merchantRelationTransfers = [];
        foreach ($merchantRelationEntities as $merchantRelationEntity) {
            $merchantRelationTransfers[] = $this->getFactory()
                ->createPropelMerchantRelationshipMapper()
                ->mapEntityToMerchantRelationshipTransfer($merchantRelationEntity, new MerchantRelationshipTransfer());
        }

        return $merchantRelationTransfers;
    }

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery $merchantRelationshipQuery
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer
     *
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function addFiltersToMerchantRelationshipQuery(
        SpyMerchantRelationshipQuery $merchantRelationshipQuery,
        MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer
    ): SpyMerchantRelationshipQuery {
        if ($merchantRelationshipFilterTransfer->getIdProductList()) {
            $merchantRelationshipQuery
                ->useSpyProductListQuery()
                    ->filterByIdProductList($merchantRelationshipFilterTransfer->getIdProductList())
                ->endUse();
        }

        return $merchantRelationshipQuery;
    }
}
