<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Generated\Shared\Transfer\CmsBlockSuggestionCollectionTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsSlotBlock\Persistence\Map\SpyCmsSlotBlockTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockPersistenceFactory getFactory()
 */
class CmsSlotBlockRepository extends AbstractRepository implements CmsSlotBlockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlocks(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): CmsSlotBlockCollectionTransfer {
        $query = $this->getFactory()->getCmsSlotBlockQuery();

        if ($cmsSlotBlockCriteriaTransfer->getIdCmsSlotTemplate()) {
            $query->filterByFkCmsSlotTemplate($cmsSlotBlockCriteriaTransfer->getIdCmsSlotTemplate());
        }

        if ($cmsSlotBlockCriteriaTransfer->getIdCmsSlot()) {
            $query->filterByFkCmsSlot($cmsSlotBlockCriteriaTransfer->getIdCmsSlot());
        }

        $cmsSlotBlockEntities = $this
            ->buildQueryFromCriteria(
                $query,
                $cmsSlotBlockCriteriaTransfer->getFilter()
            )
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->orderBy(SpyCmsSlotBlockTableMap::COL_POSITION)
            ->find();

        return $this->getFactory()
            ->createCmsSlotBlockMapper()
            ->mapCmsSlotBlockEntityCollectionToTransferCollection(
                $cmsSlotBlockEntities,
                new CmsSlotBlockCollectionTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksWithSlotRelations(FilterTransfer $filterTransfer): array
    {
        $filterTransfer = $this->setFilterTransferDefaultValues($filterTransfer);

        $cmsBlockIds = $this->getCmsBlockIds($filterTransfer);

        if (!$cmsBlockIds) {
            return [];
        }

        $cmsBlockEntities = $this
            ->buildCmsBlockWithSlotRelationQuery($cmsBlockIds)
            ->orderBy($filterTransfer->getOrderBy(), $filterTransfer->getOrderDirection())
            ->find();

        return $this->getFactory()
            ->createCmsSlotBlockMapper()
            ->mapCmsBlockEntitiesToTransfers($cmsBlockEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockSuggestionCollectionTransfer
     */
    public function getCmsBlockPaginatedSuggestionsWithSlotRelation(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer,
        PaginationTransfer $paginationTransfer
    ): CmsBlockSuggestionCollectionTransfer {
        $cmsBlockSuggestionCollectionTransfer = new CmsBlockSuggestionCollectionTransfer();

        $cmsBlockIds = $this->getCmsBlockSuggestedIds($cmsSlotBlockCriteriaTransfer, $paginationTransfer);
        if (!$cmsBlockIds) {
            return $cmsBlockSuggestionCollectionTransfer;
        }

        $cmsBlockEntities = $this
            ->buildCmsBlockWithSlotRelationQuery($cmsBlockIds)
            ->addAscendingOrderByColumn(SpyCmsBlockTableMap::COL_NAME)
            ->find();

        return $this->getFactory()
            ->createCmsSlotBlockMapper()
            ->mapCmsBlockEntitiesToCmsBlockSuggestionCollectionTransfer($cmsBlockEntities)
            ->setPagination($paginationTransfer);
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    protected function buildCmsBlockWithSlotRelationQuery(array $cmsBlockIds): SpyCmsBlockQuery
    {
        return $this->getFactory()
            ->getCmsBlockQuery()
            ->filterByIdCmsBlock_In($cmsBlockIds)
            ->groupByIdCmsBlock()
            ->leftJoinWithSpyCmsSlotBlock()
            ->useSpyCmsBlockStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinSpyStore('stores')
                ->withColumn('GROUP_CONCAT(stores.name)', CmsBlockTransfer::STORE_NAMES)
            ->endUse();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return array
     */
    protected function getCmsBlockSuggestedIds(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer,
        PaginationTransfer $paginationTransfer
    ): array {
        $cmsBlockQuery = $this->getFactory()->getCmsBlockQuery();

        $cmsBlockName = mb_strtoupper(trim($cmsSlotBlockCriteriaTransfer->getCmsBlockName()));
        if ($cmsBlockName !== '') {
            $cmsBlockQuery->where('UPPER(' . SpyCmsBlockTableMap::COL_NAME . ') LIKE ?', "%$cmsBlockName%");
        }

        $pagination = $this->getPaginationFromQuery($cmsBlockQuery, $paginationTransfer);
        $paginationTransfer->setLastPage($pagination->getLastPage());

        return $pagination->getQuery()
            ->select(SpyCmsBlockTableMap::COL_ID_CMS_BLOCK)
            ->addAscendingOrderByColumn(SpyCmsBlockTableMap::COL_NAME)
            ->setFormatter(SimpleArrayFormatter::class)
            ->find()
            ->toArray();
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery $cmsBlockQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\Util\PropelModelPager
     */
    protected function getPaginationFromQuery(
        SpyCmsBlockQuery $cmsBlockQuery,
        PaginationTransfer $paginationTransfer
    ): PropelModelPager {
        $page = $paginationTransfer->getPage();
        $maxPerPage = $paginationTransfer->getMaxPerPage();

        return $cmsBlockQuery->paginate($page, $maxPerPage);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return int[]
     */
    protected function getCmsBlockIds(FilterTransfer $filterTransfer): array
    {
        return $this->buildQueryFromCriteria($this->getFactory()->getCmsBlockQuery(), $filterTransfer)
            ->select(SpyCmsBlockTableMap::COL_ID_CMS_BLOCK)
            ->setFormatter(SimpleArrayFormatter::class)
            ->find()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function setFilterTransferDefaultValues(FilterTransfer $filterTransfer): FilterTransfer
    {
        if (!$filterTransfer->getOrderBy()) {
            $filterTransfer->setOrderBy(SpyCmsBlockTableMap::COL_NAME);
        }

        if (!$filterTransfer->getOrderDirection()) {
            $filterTransfer->setOrderDirection(Criteria::ASC);
        }

        return $filterTransfer;
    }
}
