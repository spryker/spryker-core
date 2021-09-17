<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Persistence;

use Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupTransfer;
use Orm\Zed\MerchantProductOption\Persistence\Map\SpyMerchantProductOptionGroupTableMap;
use Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig;

/**
 * @method \Spryker\Zed\MerchantProductOption\Persistence\MerchantProductOptionPersistenceFactory getFactory()
 */
class MerchantProductOptionRepository extends AbstractRepository implements MerchantProductOptionRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer
     */
    public function getGroups(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): MerchantProductOptionGroupCollectionTransfer {
        $merchantProductOptionGroupQuery = $this->getFactory()->getMerchantProductOptionGroupQuery();

        $merchantProductOptionGroupQuery = $this->applyCriteria(
            $merchantProductOptionGroupCriteriaTransfer,
            $merchantProductOptionGroupQuery
        );

        $merchantProductOptionGroupEntities = $merchantProductOptionGroupQuery->find();

        return $this->getFactory()
            ->createMerchantProductOptionGroupMapper()
            ->mapMerchantProductOptionGroupEntitiesToMerchantProductOptionGroupCollectionTransfer(
                $merchantProductOptionGroupEntities,
                new MerchantProductOptionGroupCollectionTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupTransfer|null
     */
    public function findGroup(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): ?MerchantProductOptionGroupTransfer {
        $merchantProductOptionGroupQuery = $this->getFactory()
            ->getMerchantProductOptionGroupQuery();

        $merchantProductOptionGroupQuery = $this->applyCriteria(
            $merchantProductOptionGroupCriteriaTransfer,
            $merchantProductOptionGroupQuery
        );

        $merchantProductOptionGroupEntity = $merchantProductOptionGroupQuery->findOne();

        if (!$merchantProductOptionGroupEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantProductOptionGroupMapper()
            ->mapMerchantProductOptionGroupEntityToMerchantProductOptionGroupTransfer(
                $merchantProductOptionGroupEntity,
                new MerchantProductOptionGroupTransfer()
            );
    }

    /**
     * @phpstan-return array<int|null>
     *
     * @param array<int> $productOptionGroupIds
     *
     * @return array
     */
    public function getProductOptionGroupIdsWithNotApprovedMerchantGroups(array $productOptionGroupIds): array
    {
        return $this->getFactory()
            ->getMerchantProductOptionGroupQuery()
            ->select([SpyMerchantProductOptionGroupTableMap::COL_FK_PRODUCT_OPTION_GROUP])
            ->filterByApprovalStatus(
                MerchantProductOptionConfig::STATUS_APPROVED,
                Criteria::NOT_EQUAL
            )
            ->find()
            ->getData();
    }

    /**
     * @phpstan-param \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery<mixed> $merchantProductOptionGroupQuery
     *
     * @phpstan-return \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery<mixed>
     *
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     * @param \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery $merchantProductOptionGroupQuery
     *
     * @return \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery
     */
    protected function applyCriteria(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer,
        SpyMerchantProductOptionGroupQuery $merchantProductOptionGroupQuery
    ): SpyMerchantProductOptionGroupQuery {
        if ($merchantProductOptionGroupCriteriaTransfer->getIdProductOptionGroup()) {
            $merchantProductOptionGroupQuery->filterByFkProductOptionGroup(
                $merchantProductOptionGroupCriteriaTransfer->getIdProductOptionGroup()
            );
        }

        if ($merchantProductOptionGroupCriteriaTransfer->getProductOptionGroupIds()) {
            $merchantProductOptionGroupQuery->filterByFkProductOptionGroup_In(
                $merchantProductOptionGroupCriteriaTransfer->getProductOptionGroupIds()
            );
        }

        return $merchantProductOptionGroupQuery;
    }
}
