<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Persistence;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantPersistenceFactory getFactory()
 */
class DataImportMerchantRepository extends AbstractRepository implements DataImportMerchantRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function getDataImportMerchantFileCollection(
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): DataImportMerchantFileCollectionTransfer {
        $dataImportMerchantFileQuery = $this->getFactory()->createDataImportMerchantFileQuery();
        $dataImportMerchantFileQuery = $this->applyDataImportMerchantFileFilters(
            $dataImportMerchantFileQuery,
            $dataImportMerchantFileCriteriaTransfer,
        );
        $dataImportMerchantFileQuery = $this->applyDataImportMerchantFileSearch(
            $dataImportMerchantFileQuery,
            $dataImportMerchantFileCriteriaTransfer,
        );
        $dataImportMerchantFileQuery = $this->applyDataImportMerchantFileSorting(
            $dataImportMerchantFileQuery,
            $dataImportMerchantFileCriteriaTransfer,
        );

        $dataImportMerchantFileCollectionTransfer = new DataImportMerchantFileCollectionTransfer();
        $paginationTransfer = $dataImportMerchantFileCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $dataImportMerchantFileQuery = $this->applyDataImportMerchantFilePagination(
                $dataImportMerchantFileQuery,
                $paginationTransfer,
            );
            $dataImportMerchantFileCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createDataImportMerchantFileMapper()
            ->mapDataImportMerchantFileEntityCollectionToDataImportMerchantFileCollectionTransfer(
                $dataImportMerchantFileQuery->find(),
                $dataImportMerchantFileCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery $dataImportMerchantFileQuery
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery
     */
    protected function applyDataImportMerchantFileFilters(
        SpyDataImportMerchantFileQuery $dataImportMerchantFileQuery,
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): SpyDataImportMerchantFileQuery {
        $dataImportMerchantFileConditionsTransfer = $dataImportMerchantFileCriteriaTransfer->getDataImportMerchantFileConditions();
        if (!$dataImportMerchantFileConditionsTransfer) {
            return $dataImportMerchantFileQuery;
        }

        if ($dataImportMerchantFileConditionsTransfer->getDataImportMerchantFileIds()) {
            $dataImportMerchantFileQuery->filterByIdDataImportMerchantFile_In(
                $dataImportMerchantFileConditionsTransfer->getDataImportMerchantFileIds(),
            );
        }

        if ($dataImportMerchantFileConditionsTransfer->getUuids()) {
            $dataImportMerchantFileQuery->filterByUuid_In(
                $dataImportMerchantFileConditionsTransfer->getUuids(),
            );
        }

        if ($dataImportMerchantFileConditionsTransfer->getMerchantReferences()) {
            $dataImportMerchantFileQuery->filterByMerchantReference_In(
                $dataImportMerchantFileConditionsTransfer->getMerchantReferences(),
            );
        }

        if ($dataImportMerchantFileConditionsTransfer->getStatuses()) {
            $dataImportMerchantFileQuery->filterByStatus_In(
                $dataImportMerchantFileConditionsTransfer->getStatuses(),
            );
        }

        if ($dataImportMerchantFileConditionsTransfer->getImporterTypes()) {
            $dataImportMerchantFileQuery->filterByImporterType_In(
                $dataImportMerchantFileConditionsTransfer->getImporterTypes(),
            );
        }

        if ($dataImportMerchantFileConditionsTransfer->getUserIds()) {
            $dataImportMerchantFileQuery->filterByFkUser_In(
                $dataImportMerchantFileConditionsTransfer->getUserIds(),
            );
        }

        $createdAtCriteriaRangeFilterTransfer = $dataImportMerchantFileConditionsTransfer->getRangeCreatedAt();
        if (!$createdAtCriteriaRangeFilterTransfer) {
            return $dataImportMerchantFileQuery;
        }

        if ($createdAtCriteriaRangeFilterTransfer->getFrom()) {
            $dataImportMerchantFileQuery->filterByCreatedAt(
                $createdAtCriteriaRangeFilterTransfer->getFrom(),
                Criteria::GREATER_EQUAL,
            );
        }

        if ($createdAtCriteriaRangeFilterTransfer->getTo()) {
            $dataImportMerchantFileQuery->filterByCreatedAt(
                $createdAtCriteriaRangeFilterTransfer->getTo(),
                Criteria::LESS_THAN,
            );
        }

        return $dataImportMerchantFileQuery;
    }

    /**
     * @param \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery $dataImportMerchantFileQuery
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery
     */
    protected function applyDataImportMerchantFileSorting(
        SpyDataImportMerchantFileQuery $dataImportMerchantFileQuery,
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): SpyDataImportMerchantFileQuery {
        $sortCollection = $dataImportMerchantFileCriteriaTransfer->getSortCollection();
        foreach ($sortCollection as $sortTransfer) {
            $dataImportMerchantFileQuery->orderBy($sortTransfer->getFieldOrFail(), $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC);
        }

        return $dataImportMerchantFileQuery;
    }

    /**
     * @param \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery $dataImportMerchantFileQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyDataImportMerchantFilePagination(
        SpyDataImportMerchantFileQuery $dataImportMerchantFileQuery,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            $paginationTransfer->setNbResults($dataImportMerchantFileQuery->count());

            return $dataImportMerchantFileQuery
                ->limit($paginationTransfer->getLimitOrFail())
                ->offset($paginationTransfer->getOffsetOrFail());
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage() !== null) {
            $propelModelPager = $dataImportMerchantFileQuery->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer->setNbResults($propelModelPager->getNbResults())
                ->setFirstIndex($propelModelPager->getFirstIndex())
                ->setLastIndex($propelModelPager->getLastIndex())
                ->setFirstPage($propelModelPager->getFirstPage())
                ->setLastPage($propelModelPager->getLastPage())
                ->setNextPage($propelModelPager->getNextPage())
                ->setPreviousPage($propelModelPager->getPreviousPage());

            return $propelModelPager->getQuery();
        }

        return $dataImportMerchantFileQuery;
    }

    /**
     * @param \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery $dataImportMerchantFileQuery
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery
     */
    protected function applyDataImportMerchantFileSearch(
        SpyDataImportMerchantFileQuery $dataImportMerchantFileQuery,
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): SpyDataImportMerchantFileQuery {
        $dataImportMerchantFileSearchConditionsTransfer = $dataImportMerchantFileCriteriaTransfer->getDataImportMerchantFileSearchConditions();
        if (!$dataImportMerchantFileSearchConditionsTransfer) {
            return $dataImportMerchantFileQuery;
        }

        if ($dataImportMerchantFileSearchConditionsTransfer->getOriginalFileName()) {
            $dataImportMerchantFileQuery->setIgnoreCase(true);
            $dataImportMerchantFileQuery->filterByOriginalFileName_Like(sprintf(
                '%%%s%%',
                $dataImportMerchantFileSearchConditionsTransfer->getOriginalFileName(),
            ));
        }

        return $dataImportMerchantFileQuery;
    }
}
