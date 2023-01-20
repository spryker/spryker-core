<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Persistence;

use Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
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
     * @deprecated Will be removed without replacement.
     *
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
            $merchantProductOptionGroupQuery,
        );

        $merchantProductOptionGroupEntities = $merchantProductOptionGroupQuery->find();

        return $this->getFactory()
            ->createMerchantProductOptionGroupMapper()
            ->mapMerchantProductOptionGroupEntitiesToMerchantProductOptionGroupCollectionTransfer(
                $merchantProductOptionGroupEntities,
                new MerchantProductOptionGroupCollectionTransfer(),
            );
    }

    /**
     * @param array<int> $productOptionGroupIds
     *
     * @return array<int|null>
     */
    public function getProductOptionGroupIdsWithNotApprovedMerchantGroups(array $productOptionGroupIds): array
    {
        return $this->getFactory()
            ->getMerchantProductOptionGroupQuery()
            ->select([SpyMerchantProductOptionGroupTableMap::COL_FK_PRODUCT_OPTION_GROUP])
            ->filterByApprovalStatus(
                MerchantProductOptionConfig::STATUS_APPROVED,
                Criteria::NOT_EQUAL,
            )
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer
     */
    public function getMerchantProductOptionGroupCollection(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): MerchantProductOptionGroupCollectionTransfer {
        $merchantProductOptionCollectionTransfer = new MerchantProductOptionGroupCollectionTransfer();
        $merchantProductOptionGroupQuery = $this->getFactory()->getMerchantProductOptionGroupQuery();

        $merchantProductOptionGroupQuery = $this->applyMerchantProductOptionGroupFilters(
            $merchantProductOptionGroupCriteriaTransfer,
            $merchantProductOptionGroupQuery,
        );

        $paginationTransfer = $merchantProductOptionGroupCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $merchantProductOptionGroupQuery = $this
                ->applyMerchantProductOptionGroupPagination($merchantProductOptionGroupQuery, $paginationTransfer);
            $merchantProductOptionCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createMerchantProductOptionGroupMapper()
            ->mapMerchantProductOptionGroupEntitiesToMerchantProductOptionGroupCollectionTransfer(
                $merchantProductOptionGroupQuery->find(),
                $merchantProductOptionCollectionTransfer,
            );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     * @param \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery<mixed> $merchantProductOptionGroupQuery
     *
     * @return \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery<mixed>
     */
    protected function applyCriteria(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer,
        SpyMerchantProductOptionGroupQuery $merchantProductOptionGroupQuery
    ): SpyMerchantProductOptionGroupQuery {
        if ($merchantProductOptionGroupCriteriaTransfer->getIdProductOptionGroup()) {
            $merchantProductOptionGroupQuery->filterByFkProductOptionGroup(
                $merchantProductOptionGroupCriteriaTransfer->getIdProductOptionGroup(),
            );
        }

        if ($merchantProductOptionGroupCriteriaTransfer->getProductOptionGroupIds()) {
            $merchantProductOptionGroupQuery->filterByFkProductOptionGroup_In(
                $merchantProductOptionGroupCriteriaTransfer->getProductOptionGroupIds(),
            );
        }

        return $merchantProductOptionGroupQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     * @param \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery $merchantProductOptionGroupQuery
     *
     * @return \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery
     */
    protected function applyMerchantProductOptionGroupFilters(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer,
        SpyMerchantProductOptionGroupQuery $merchantProductOptionGroupQuery
    ): SpyMerchantProductOptionGroupQuery {
        $merchantProductOptionGroupConditionsTransfer = $merchantProductOptionGroupCriteriaTransfer->getMerchantProductOptionGroupConditions();
        if ($merchantProductOptionGroupConditionsTransfer === null) {
            return $merchantProductOptionGroupQuery;
        }

        if ($merchantProductOptionGroupConditionsTransfer->getProductOptionGroupIds()) {
            $merchantProductOptionGroupQuery->filterByFkProductOptionGroup_In(
                $merchantProductOptionGroupConditionsTransfer->getProductOptionGroupIds(),
            );
        }

        return $merchantProductOptionGroupQuery;
    }

    /**
     * @param \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery $merchantProductOptionGroupQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery
     */
    protected function applyMerchantProductOptionGroupPagination(
        SpyMerchantProductOptionGroupQuery $merchantProductOptionGroupQuery,
        PaginationTransfer $paginationTransfer
    ): SpyMerchantProductOptionGroupQuery {
        $paginationTransfer->setNbResults($merchantProductOptionGroupQuery->count());
        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $merchantProductOptionGroupQuery
                ->limit($paginationTransfer->getLimit())
                ->offset($paginationTransfer->getOffset());
        }

        return $merchantProductOptionGroupQuery;
    }
}
