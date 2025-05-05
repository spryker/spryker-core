<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\FileManager\Persistence\Map\SpyFileInfoTableMap;
use Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementPersistenceFactory getFactory()
 */
class SspFileManagementRepository extends AbstractRepository implements SspFileManagementRepositoryInterface
{
    /**
     * @var array<string>
     */
    protected const ORDER_BY_MAPPING = [
        'fileType' => SpyFileInfoTableMap::COL_EXTENSION,
        'size' => SpyFileInfoTableMap::COL_SIZE,
        'createdAt' => SpyFileInfoTableMap::COL_CREATED_AT,
    ];

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollection(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): FileAttachmentCollectionTransfer
    {
        $fileAttachmentCollectionTransfer = new FileAttachmentCollectionTransfer();

        $companyFiles = $this->getCompanyFiles($fileAttachmentCriteriaTransfer);
        $companyUserFiles = $this->getCompanyUserFiles($fileAttachmentCriteriaTransfer);
        $companyBusinessUnitFiles = $this->getCompanyBusinessUnitFiles($fileAttachmentCriteriaTransfer);
        $sspAssetFiles = $this->getSspAssetFiles($fileAttachmentCriteriaTransfer);

        $fileAttachmentTransfers = array_merge(
            $this->getFactory()->createCompanyFileMapper()->mapCompanyFileEntitiesToFileAttachmentTransfers($companyFiles),
            $this->getFactory()->createCompanyUserFileMapper()->mapCompanyUserFileEntitiesToFileAttachmentTransfers($companyUserFiles),
            $this->getFactory()->createCompanyBusinessUnitFileMapper()->mapCompanyBusinessUnitFileEntitiesToFileAttachmentTransfers($companyBusinessUnitFiles),
            $this->getFactory()->createSspAssetFileMapper()->mapSspAssetFileEntitiesToFileAttachmentTransfers($sspAssetFiles),
        );

        return $fileAttachmentCollectionTransfer->setFileAttachments(new ArrayObject($fileAttachmentTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFile>
     */
    protected function getCompanyFiles(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): ObjectCollection
    {
        $query = $this->getFactory()
            ->createCompanyFileQuery();

        $idFiles = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getIdFiles();

        if ($idFiles !== null) {
            $query->filterByFkFile_In($idFiles);
        }

        return $query->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFile>
     */
    protected function getCompanyUserFiles(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): ObjectCollection
    {
        $query = $this->getFactory()
            ->createCompanyUserFileQuery();

        $idFiles = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getIdFiles();

        if ($idFiles !== null) {
            $query->filterByFkFile_In($idFiles);
        }

        return $query->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFile>
     */
    protected function getCompanyBusinessUnitFiles(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): ObjectCollection
    {
        $query = $this->getFactory()
            ->createCompanyBusinessUnitFileQuery();

        $idFiles = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getIdFiles();

        if ($idFiles !== null) {
            $query->filterByFkFile_In($idFiles);
        }

        return $query->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SspFileManagement\Persistence\SpySspAssetFile>
     */
    protected function getSspAssetFiles(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): ObjectCollection
    {
        $query = $this->getFactory()
            ->createSspAssetFileQuery();

        $idFiles = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getIdFiles();

        if ($idFiles !== null) {
            $query->filterByFkFile_In($idFiles);
        }

        return $query->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     * @param array<\SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface> $queryStrategies
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    public function getFileAttachmentFileCollectionAccordingToPermissions(
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer,
        array $queryStrategies
    ): FileAttachmentFileCollectionTransfer {
        $query = $this->getFactory()
            ->getFilePropelQuery()
            ->leftJoinSpyFileInfo()
            ->groupBy(SpyFileTableMap::COL_ID_FILE);

        $query = $this->applyPermissionStrategies($query, $fileAttachmentFileCriteriaTransfer, $queryStrategies);
        $query = $this->applyFileSearch($query, $fileAttachmentFileCriteriaTransfer);
        $query = $this->applyFileTypeFilter($query, $fileAttachmentFileCriteriaTransfer);
        $query = $this->applyDateRangeFilter($query, $fileAttachmentFileCriteriaTransfer);
        $query = $this->applyFileUuidFilter($query, $fileAttachmentFileCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $fileAttachmentFileCriteriaTransfer->getSortCollection();
        $query = $this->applySorting($query, $sortTransfers);

        $fileAttachmentFileCollectionTransfer = new FileAttachmentFileCollectionTransfer();
        $paginationTransfer = $fileAttachmentFileCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $query = $this->applyPagination($query, $paginationTransfer);
            $fileAttachmentFileCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createFileMapper()
            ->mapEntityCollectionToTransferCollection(
                $query->find(),
                $fileAttachmentFileCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     * @param array<\SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface> $queryStrategies
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyPermissionStrategies(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer,
        array $queryStrategies
    ): SpyFileQuery {
        foreach ($queryStrategies as $strategy) {
            $query = $strategy->apply($query, $fileAttachmentFileCriteriaTransfer);
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyFileSearch(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): SpyFileQuery {
        $searchString = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileSearchConditionsOrFail()->getSearchString();

        if ($searchString) {
            $query->filterByFileName_Like('%' . $searchString . '%')
                ->_or()
                ->filterByFileReference_Like('%' . $searchString . '%');
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyFileTypeFilter(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): SpyFileQuery {
        $fileTypes = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getFileTypes();

        if (!$fileTypes) {
            return $query;
        }

        return $query
            ->useSpyFileInfoQuery()
                ->filterByExtension_In($fileTypes)
            ->endUse();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyDateRangeFilter(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): SpyFileQuery {
        $rangeCreatedAt = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getRangeCreatedAt();
        if (!$rangeCreatedAt) {
            return $query;
        }

        if ($rangeCreatedAt->getFrom()) {
            $query->useSpyFileInfoQuery()
                    ->filterByCreatedAt($rangeCreatedAt->getFrom(), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($rangeCreatedAt->getTo()) {
            $query->useSpyFileInfoQuery()
                    ->filterByCreatedAt($rangeCreatedAt->getTo(), Criteria::LESS_EQUAL)
                ->endUse();
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function applyFileUuidFilter(
        SpyFileQuery $query,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): SpyFileQuery {
        $uuids = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getUuids();
        if ($uuids !== []) {
            $query
                ->filterByUuid_In($uuids);
        }

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(ModelCriteria $query, PaginationTransfer $paginationTransfer): ModelCriteria
    {
        if ($paginationTransfer->getLimit() === null || $paginationTransfer->getOffset() === null) {
            $paginationTransfer = $this->getPaginationTransfer($query, $paginationTransfer);
        }

        $query
            ->setLimit($paginationTransfer->getLimitOrFail())
            ->setOffset($paginationTransfer->getOffsetOrFail());

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPaginationTransfer(
        ModelCriteria $query,
        PaginationTransfer $paginationTransfer
    ): PaginationTransfer {
        $page = $paginationTransfer->getPage() ?? 1;
        $maxPerPage = $paginationTransfer->getMaxPerPageOrFail();
        $paginationModel = $query->paginate($page, $maxPerPage);
        $nbResults = $paginationModel->getNbResults();

        return $paginationTransfer
            ->setNbResults($nbResults)
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage())
            ->setOffset(($page - 1) * $maxPerPage)
            ->setLimit($maxPerPage);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySorting(ModelCriteria $query, ArrayObject $sortTransfers): ModelCriteria
    {
        foreach ($sortTransfers as $sortTransfer) {
            $query
                ->groupBy(static::ORDER_BY_MAPPING[$sortTransfer->getFieldOrFail()] ?? $sortTransfer->getFieldOrFail())
                ->orderBy(
                    static::ORDER_BY_MAPPING[$sortTransfer->getFieldOrFail()] ?? $sortTransfer->getFieldOrFail(),
                    $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
                );
        }

        return $query;
    }
}
