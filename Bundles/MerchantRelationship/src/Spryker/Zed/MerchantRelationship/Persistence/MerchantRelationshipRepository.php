<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipPersistenceFactory getFactory()
 */
class MerchantRelationshipRepository extends AbstractRepository implements MerchantRelationshipRepositoryInterface
{
    protected const COL_MAX_ID = 'MAX_ID';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idMerchantRelationship
     *
     * @throws \Spryker\Zed\MerchantRelationship\Business\Exception\MerchantRelationshipNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function getMerchantRelationshipById(int $idMerchantRelationship): ?MerchantRelationshipTransfer
    {
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
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idBusinessUnit
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollectionByIdAssignedBusinessUnit(int $idBusinessUnit): array
    {
        $merchantRelationshipEntities = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->useSpyMerchantRelationshipToCompanyBusinessUnitQuery()
                ->filterByFkCompanyBusinessUnit($idBusinessUnit)
            ->endUse()
            ->find();
        if ($merchantRelationshipEntities->count() === 0) {
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
}
