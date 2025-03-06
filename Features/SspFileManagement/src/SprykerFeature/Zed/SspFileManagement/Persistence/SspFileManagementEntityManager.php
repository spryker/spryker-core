<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence;

use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementPersistenceFactory getFactory()
 */
class SspFileManagementEntityManager extends AbstractEntityManager implements SspFileManagementEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteFileAttachmentCollection(
        FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
    ): void {
        /** @var list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList */
        $fileAttachmentQueryList = $this->getFactory()->getFileAttachmentQueryList();
        if ($fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyIds() !== []) {
            $fileAttachmentQueryList = $this->applyFileAttachmentByCompanyIdsCondition($fileAttachmentQueryList, $fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyIds());
        }

        if ($fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyUserIds() !== []) {
            $fileAttachmentQueryList = $this->applyFileAttachmentByCompanyUserIdsCondition($fileAttachmentQueryList, $fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyUserIds());
        }

        if ($fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyBusinessUnitIds() !== []) {
            $fileAttachmentQueryList = $this->applyFileAttachmentByCompanyBusinessUnitIdsCondition($fileAttachmentQueryList, $fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyBusinessUnitIds());
        }

        if ($fileAttachmentCollectionDeleteCriteriaTransfer->getFileIds() !== []) {
            $fileAttachmentQueryList = $this->applyFileAttachmentByFileIdsCondition(
                $fileAttachmentQueryList,
                $fileAttachmentCollectionDeleteCriteriaTransfer->getFileIds(),
                count($fileAttachmentCollectionDeleteCriteriaTransfer->modifiedToArray()) > 1,
            );
        }

        $this->deleteFileAttachments($fileAttachmentQueryList);
    }

    /**
     * @param list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList
     * @param list<int> $fileIds
     * @param bool $applyOnlyToModifiedQueries
     *
     * @return list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery>
     */
    protected function applyFileAttachmentByFileIdsCondition(
        array $fileAttachmentQueryList,
        array $fileIds,
        bool $applyOnlyToModifiedQueries
    ): array {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if ($applyOnlyToModifiedQueries && !$fileAttachmentQuery->hasWhereClause()) {
                continue;
            }

            $fileAttachmentQuery->filterByFkFile_In($fileIds);
        }

        return $fileAttachmentQueryList;
    }

    /**
     * @param list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList
     * @param list<int> $companyIds
     *
     * @return list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery>
     */
    protected function applyFileAttachmentByCompanyIdsCondition(
        array $fileAttachmentQueryList,
        array $companyIds
    ): array {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if ($fileAttachmentQuery instanceof SpyCompanyFileQuery) {
                $fileAttachmentQuery->filterByFkCompany_In($companyIds);
            }
        }

        return $fileAttachmentQueryList;
    }

    /**
     * @param list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList
     * @param list<int> $companyUserIds
     *
     * @return list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery>
     */
    protected function applyFileAttachmentByCompanyUserIdsCondition(
        array $fileAttachmentQueryList,
        array $companyUserIds
    ): array {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if ($fileAttachmentQuery instanceof SpyCompanyUserFileQuery) {
                $fileAttachmentQuery->filterByFkCompanyUser_In($companyUserIds);
            }
        }

        return $fileAttachmentQueryList;
    }

    /**
     * @param list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList
     * @param list<int> $companyBusinessUnitIds
     *
     * @return list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery>
     */
    protected function applyFileAttachmentByCompanyBusinessUnitIdsCondition(
        array $fileAttachmentQueryList,
        array $companyBusinessUnitIds
    ): array {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if ($fileAttachmentQuery instanceof SpyCompanyBusinessUnitFileQuery) {
                $fileAttachmentQuery->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds);
            }
        }

        return $fileAttachmentQueryList;
    }

    /**
     * @param list<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList
     *
     * @return void
     */
    protected function deleteFileAttachments(array $fileAttachmentQueryList): void
    {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if (!$fileAttachmentQuery->hasWhereClause()) {
                continue;
            }

            $fileAttachmentQuery->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function saveFileAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        $saverFactory = $this->getFactory()->createFileAttachmentSaverFactory();
        $saver = $saverFactory->create($fileAttachmentTransfer->getEntityNameOrFail());

        return $saver->save($fileAttachmentTransfer);
    }
}
