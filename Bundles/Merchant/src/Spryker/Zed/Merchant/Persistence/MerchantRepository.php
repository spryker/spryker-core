<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantPersistenceFactory getFactory()
 */
class MerchantRepository extends AbstractRepository implements MerchantRepositoryInterface
{
    protected const DEFAULT_ORDER_COLUMN = SpyMerchantTableMap::COL_NAME;

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): MerchantCollectionTransfer
    {
        $merchantQuery = $this->getFactory()->createMerchantQuery();

        $filterTransfer = $merchantCriteriaTransfer->getFilter();
        if ($filterTransfer === null || empty($filterTransfer->getOrderBy())) {
            $filterTransfer = (new FilterTransfer())->setOrderBy(static::DEFAULT_ORDER_COLUMN);
        }

        $merchantQuery = $this->applyFilters($merchantQuery, $merchantCriteriaTransfer);
        $merchantQuery = $this->buildQueryFromCriteria($merchantQuery, $filterTransfer)->setFormatter(ObjectFormatter::class);

        /** @var \Orm\Zed\Merchant\Persistence\SpyMerchant[] $merchantCollection */
        $merchantCollection = $this->getPaginatedCollection($merchantQuery, $merchantCriteriaTransfer->getPagination());

        $merchantCollectionTransfer = $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapMerchantCollectionToMerchantCollectionTransfer($merchantCollection, new MerchantCollectionTransfer());

        $merchantCollectionTransfer->setPagination($merchantCriteriaTransfer->getPagination());

        return $merchantCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findOne(MerchantCriteriaTransfer $merchantCriteriaTransfer): ?MerchantTransfer
    {
        $merchantQuery = $this->getFactory()->createMerchantQuery();
        $merchantEntity = $this->applyFilters($merchantQuery, $merchantCriteriaTransfer)->findOne();

        if (!$merchantEntity) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapMerchantEntityToMerchantTransfer($merchantEntity, new MerchantTransfer());
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer[]
     */
    public function getMerchantStoreRelationMapByMerchantIds(array $merchantIds): array
    {
        /** @var \Generated\Shared\Transfer\StoreRelationTransfer[] $storeRelationTransfers */
        $storeRelationTransfers = [];

        $merchantStoreEntities = $this->getFactory()
            ->createMerchantStoreQuery()
            ->joinWithSpyStore()
            ->filterByFkMerchant_In($merchantIds)
            ->find();

        foreach ($merchantIds as $idMerchant) {
            $storeRelationTransfers[$idMerchant] = $this->getFactory()
                ->createPropelMerchantMapper()
                ->mapMerchantStoreEntitiesToStoreRelationTransfer(
                    $this->filterMerchantStoresById($merchantStoreEntities, $idMerchant),
                    (new StoreRelationTransfer())->setIdEntity($idMerchant)
                );
        }

        return $storeRelationTransfers;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\Base\SpyMerchantStore[]|\Propel\Runtime\Collection\ObjectCollection $merchantStoreEntities
     * @param int $idMerchant
     *
     * @return \Orm\Zed\Merchant\Persistence\Base\SpyMerchantStore[]
     */
    protected function filterMerchantStoresById(ObjectCollection $merchantStoreEntities, int $idMerchant): array
    {
        $filteredMerchantStoreEntities = [];
        foreach ($merchantStoreEntities as $merchantStoreEntity) {
            if ($merchantStoreEntity->getFkMerchant() === $idMerchant) {
                $filteredMerchantStoreEntities[] = $merchantStoreEntity;
            }
        }

        return $filteredMerchantStoreEntities;
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[][]
     */
    public function getUrlsMapByMerchantIds(array $merchantIds): array
    {
        $merchantUrlTransfersMap = [];

        $urlEntities = $this->getFactory()
            ->getUrlPropelQuery()
            ->joinWithSpyLocale()
            ->filterByFkResourceMerchant_In($merchantIds)
            ->find();

        if (!$urlEntities->count()) {
            return $merchantUrlTransfersMap;
        }

        foreach ($urlEntities as $urlEntity) {
            $merchantUrlTransfersMap[$urlEntity->getFkResourceMerchant()][] = $this->getFactory()
                ->createPropelMerchantMapper()
                ->mapUrlEntityToUrlTransfer($urlEntity, new UrlTransfer());
        }

        return $merchantUrlTransfersMap;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantQuery $merchantQuery
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function applyFilters(SpyMerchantQuery $merchantQuery, MerchantCriteriaTransfer $merchantCriteriaTransfer): SpyMerchantQuery
    {
        if ($merchantCriteriaTransfer->getIdMerchant() !== null) {
            $merchantQuery->filterByIdMerchant($merchantCriteriaTransfer->getIdMerchant());
        }

        if ($merchantCriteriaTransfer->getEmail() !== null) {
            $merchantQuery->filterByEmail($merchantCriteriaTransfer->getEmail());
        }

        if ($merchantCriteriaTransfer->getMerchantReference() !== null) {
            $merchantQuery->filterByMerchantReference($merchantCriteriaTransfer->getMerchantReference());
        }

        if ($merchantCriteriaTransfer->getMerchantReferences()) {
            $merchantQuery->filterByMerchantReference_In($merchantCriteriaTransfer->getMerchantReferences());
        }

        if ($merchantCriteriaTransfer->getMerchantIds()) {
            $merchantQuery->filterByIdMerchant_In($merchantCriteriaTransfer->getMerchantIds());
        }

        if ($merchantCriteriaTransfer->getIsActive() !== null) {
            $merchantQuery->filterByIsActive($merchantCriteriaTransfer->getIsActive());
        }

        if ($merchantCriteriaTransfer->getStore() !== null) {
            $merchantQuery->useSpyMerchantStoreQuery()
                    ->useSpyStoreQuery()
                        ->filterByName($merchantCriteriaTransfer->getStore()->getName())
                    ->endUse()
                ->endUse();
        }

        return $merchantQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer !== null) {
            $page = $paginationTransfer
                ->requirePage()
                ->getPage();

            $maxPerPage = $paginationTransfer
                ->requireMaxPerPage()
                ->getMaxPerPage();

            $paginationModel = $query->paginate($page, $maxPerPage);

            $paginationTransfer->setNbResults($paginationModel->getNbResults());
            $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
            $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
            $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
            $paginationTransfer->setLastPage($paginationModel->getLastPage());
            $paginationTransfer->setNextPage($paginationModel->getNextPage());
            $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getResults();
        }

        return $query->find();
    }
}
