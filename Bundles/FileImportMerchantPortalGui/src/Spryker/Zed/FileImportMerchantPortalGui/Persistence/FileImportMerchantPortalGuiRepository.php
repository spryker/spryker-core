<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantFileImportCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileImportConditionsTransfer;
use Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\FileImportMerchantPortalGui\Persistence\Map\SpyMerchantFileImportTableMap;
use Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\EntityNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiPersistenceFactory getFactory()
 */
class FileImportMerchantPortalGuiRepository extends AbstractRepository implements FileImportMerchantPortalGuiRepositoryInterface
{
    /**
     * @param int $idMerchantFileImport
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function getMerchantFileImportById(int $idMerchantFileImport): MerchantFileImportTransfer
    {
        $merchantFileImportEntity = $this->getFactory()
            ->createMerchantFileImportQuery()
            ->findOneByIdMerchantFileImport($idMerchantFileImport);

        if ($merchantFileImportEntity === null) {
            throw new EntityNotFoundException();
        }

        return $this->getFactory()
            ->createMerchantFileImportMapper()
            ->mapEntityToTransfer($merchantFileImportEntity, new MerchantFileImportTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    public function getMerchantFileImportCollection(
        MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
    ): MerchantFileImportCollectionTransfer {
        $merchantFileImportQuery = $this->getFactory()->createMerchantFileImportQuery();

        if ($merchantFileImportCriteriaTransfer->getMerchantFileImportConditions()) {
            $merchantFileImportQuery = $this->applyMerchantFileImportConditions(
                $merchantFileImportCriteriaTransfer->getMerchantFileImportConditions(),
                $merchantFileImportQuery,
            );
        }

        if ($merchantFileImportCriteriaTransfer->getLimit()) {
            $merchantFileImportQuery->limit($merchantFileImportCriteriaTransfer->getLimit());
        }

        $merchantFileImportEntities = $merchantFileImportQuery->find();

        return $this->getFactory()
            ->createMerchantFileImportMapper()
            ->mapEntityCollectionToTransfer(
                $merchantFileImportEntities,
                new MerchantFileImportCollectionTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportConditionsTransfer $merchantFileImportConditionsTransfer
     * @param \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery $merchantFileImportQuery
     *
     * @return \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery
     */
    protected function applyMerchantFileImportConditions(
        MerchantFileImportConditionsTransfer $merchantFileImportConditionsTransfer,
        SpyMerchantFileImportQuery $merchantFileImportQuery
    ): SpyMerchantFileImportQuery {
        if ($merchantFileImportConditionsTransfer->getMerchantFileImportIds()) {
            $merchantFileImportQuery->filterByIdMerchantFileImport_In($merchantFileImportConditionsTransfer->getMerchantFileImportIds());
        }

        if ($merchantFileImportConditionsTransfer->getMerchantFileIds()) {
            $merchantFileImportQuery->filterByFkMerchantFile_In($merchantFileImportConditionsTransfer->getMerchantFileIds());
        }

        if ($merchantFileImportConditionsTransfer->getUuids()) {
            $merchantFileImportQuery->filterByUuid_In($merchantFileImportConditionsTransfer->getUuids());
        }

        if ($merchantFileImportConditionsTransfer->getEntityTypes()) {
            $merchantFileImportQuery->filterByEntityType_In($merchantFileImportConditionsTransfer->getEntityTypes());
        }

        if ($merchantFileImportConditionsTransfer->getStatuses()) {
            $merchantFileImportQuery->filterByStatus_In($merchantFileImportConditionsTransfer->getStatuses());
        }

        return $merchantFileImportQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    public function getMerchantFileImportTableData(
        MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
    ): MerchantFileImportCollectionTransfer {
        $merchantFileImportQuery = $this->buildMerchantFileImportTableBaseQuery();

        $merchantFileImportQuery = $this->applyMerchantFileImportSearch(
            $merchantFileImportQuery,
            $merchantFileImportTableCriteriaTransfer,
        );
        $merchantFileImportQuery = $this->addMerchantFileImportSorting(
            $merchantFileImportQuery,
            $merchantFileImportTableCriteriaTransfer,
        );
        $merchantFileImportQuery = $this->addMerchantFileImportFilters(
            $merchantFileImportQuery,
            $merchantFileImportTableCriteriaTransfer,
        );

        $propelPager = $merchantFileImportQuery->paginate(
            $merchantFileImportTableCriteriaTransfer->getPageOrFail(),
            $merchantFileImportTableCriteriaTransfer->getPageSizeOrFail(),
        );

        $merchantFileImportTableDataMapper = $this->getFactory()
            ->createMerchantFileImportTableDataMapper();

        $merchantFileImportCollectionTransfer = $merchantFileImportTableDataMapper
            ->mapMerchantFileImportEntityArrayToCollectionTransfer(
                $propelPager->getResults()->getData(),
                new MerchantFileImportCollectionTransfer(),
            );

        $paginationTransfer = $merchantFileImportTableDataMapper->mapPropelModelPagerToPaginationTransfer(
            $propelPager,
            new PaginationTransfer(),
        );

        $merchantFileImportCollectionTransfer->setPagination($paginationTransfer);

        return $merchantFileImportCollectionTransfer;
    }

    /**
     * @return \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery
     */
    protected function buildMerchantFileImportTableBaseQuery(): SpyMerchantFileImportQuery
    {
        $merchantFileImportQuery = $this->getFactory()->createMerchantFileImportQuery();
        $merchantFileImportQuery->joinWithSpyMerchantFile();

        $merchantFileImportQuery
            ->useSpyMerchantFileQuery()
                ->joinWithUser()
            ->endUse();

        return $merchantFileImportQuery;
    }

    /**
     * @param \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery $merchantFileImportQuery
     * @param \Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
     *
     * @return \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery
     */
    protected function applyMerchantFileImportSearch(
        SpyMerchantFileImportQuery $merchantFileImportQuery,
        MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
    ): SpyMerchantFileImportQuery {
        $searchTerm = $merchantFileImportTableCriteriaTransfer->getSearchTerm();

        if (!$searchTerm) {
            return $merchantFileImportQuery;
        }

        $merchantFileImportQuery
            ->useSpyMerchantFileQuery()
                ->filterByOriginalFileName_Like('%' . $searchTerm . '%')
            ->endUse();

        return $merchantFileImportQuery;
    }

    /**
     * @param \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery $merchantFileImportQuery
     * @param \Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
     *
     * @return \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery
     */
    protected function addMerchantFileImportSorting(
        SpyMerchantFileImportQuery $merchantFileImportQuery,
        MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
    ): SpyMerchantFileImportQuery {
        $orderBy = $merchantFileImportTableCriteriaTransfer->getOrderBy() ?? SpyMerchantFileImportTableMap::COL_CREATED_AT;
        $orderDirection = $merchantFileImportTableCriteriaTransfer->getOrderDirection() ?? Criteria::DESC;

        $merchantFileImportQuery->orderBy($orderBy, $orderDirection);

        return $merchantFileImportQuery;
    }

    /**
     * @param \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery $merchantFileImportQuery
     * @param \Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
     *
     * @return \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery
     */
    protected function addMerchantFileImportFilters(
        SpyMerchantFileImportQuery $merchantFileImportQuery,
        MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
    ): SpyMerchantFileImportQuery {
        if ($merchantFileImportTableCriteriaTransfer->getFilterStatuses()) {
            $merchantFileImportQuery->filterByStatus_In($merchantFileImportTableCriteriaTransfer->getFilterStatuses());
        }

        if ($merchantFileImportTableCriteriaTransfer->getFilterEntityTypes()) {
            $merchantFileImportQuery->filterByEntityType_In($merchantFileImportTableCriteriaTransfer->getFilterEntityTypes());
        }

        if ($merchantFileImportTableCriteriaTransfer->getFilterCreatedAt() && $merchantFileImportTableCriteriaTransfer->getFilterCreatedAt()->getFrom()) {
            $merchantFileImportQuery->filterByCreatedAt(
                $merchantFileImportTableCriteriaTransfer->getFilterCreatedAt()->getFrom(),
                Criteria::GREATER_EQUAL,
            );
        }

        if ($merchantFileImportTableCriteriaTransfer->getFilterCreatedAt() && $merchantFileImportTableCriteriaTransfer->getFilterCreatedAt()->getTo()) {
            $merchantFileImportQuery->filterByCreatedAt(
                $merchantFileImportTableCriteriaTransfer->getFilterCreatedAt()->getTo(),
                Criteria::LESS_EQUAL,
            );
        }

        return $merchantFileImportQuery;
    }
}
