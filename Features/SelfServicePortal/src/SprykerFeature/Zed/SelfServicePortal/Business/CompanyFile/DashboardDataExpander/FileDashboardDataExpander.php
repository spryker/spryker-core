<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\DashboardDataExpander;

use Generated\Shared\Transfer\DashboardComponentFilesTransfer;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentSearchConditionsTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class FileDashboardDataExpander implements FileDashboardDataExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReaderInterface $fileReader
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $config
     */
    public function __construct(
        protected CompanyFileReaderInterface $fileReader,
        protected SelfServicePortalConfig $config
    ) {
    }

     /**
      * @param \Generated\Shared\Transfer\DashboardResponseTransfer $dashboardResponseTransfer
      * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
      *
      * @return \Generated\Shared\Transfer\DashboardResponseTransfer
      */
    public function provideFileAttachmentDashboardData(
        DashboardResponseTransfer $dashboardResponseTransfer,
        DashboardRequestTransfer $dashboardRequestTransfer
    ): DashboardResponseTransfer {
        $fileAttachmentCriteriaTransfer = $this->createFileAttachmentCriteriaTransfer($dashboardRequestTransfer);

        $fileAttachmentCollectionTransfer = $this->fileReader->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        $dashboardComponentFilesTransfer = (new DashboardComponentFilesTransfer())
            ->setFileAttachmentCollection($fileAttachmentCollectionTransfer);

        return $dashboardResponseTransfer->setDashboardComponentFiles($dashboardComponentFilesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer
     */
    protected function createFileAttachmentCriteriaTransfer(
        DashboardRequestTransfer $dashboardRequestTransfer
    ): FileAttachmentCriteriaTransfer {
        return (new FileAttachmentCriteriaTransfer())
            ->setFileAttachmentSearchConditions(
                (new FileAttachmentSearchConditionsTransfer()),
            )
            ->setPagination(
                (new PaginationTransfer())
                    ->setMaxPerPage($this->config->getDefaultFileDashboardMaxPerPage())
                    ->setPage($this->config->getDefaultFileDashboardPageNumber()),
            )
            ->setCompanyUser($dashboardRequestTransfer->getCompanyUser())
            ->addSort(
                (new SortTransfer())
                    ->setField($this->config->getDefaultFileDashboardSortField())
                    ->setIsAscending($this->config->isDefaultFileDashboardSortAscending()),
            )
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true)
            ->setWithCompanyUserRelation(true)
            ->setWithSspAssetRelation(true);
    }
}
