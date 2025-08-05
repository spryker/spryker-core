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
use Generated\Shared\Transfer\SortTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class FileDashboardDataExpander implements FileDashboardDataExpanderInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_FILE_DASHBOARD_PAGE_NUMBER = 1;

    public function __construct(
        protected CompanyFileReaderInterface $fileReader,
        protected SelfServicePortalConfig $config
    ) {
    }

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

    protected function createFileAttachmentCriteriaTransfer(
        DashboardRequestTransfer $dashboardRequestTransfer
    ): FileAttachmentCriteriaTransfer {
        return (new FileAttachmentCriteriaTransfer())
            ->setFileAttachmentSearchConditions(
                (new FileAttachmentSearchConditionsTransfer()),
            )
            ->setPagination(
                $dashboardRequestTransfer->getPagination(),
            )
            ->setCompanyUser($dashboardRequestTransfer->getCompanyUser())
            ->addSort(
                (new SortTransfer())
                    ->setField($this->config->getDefaultFileDashboardSortField())
                    ->setIsAscending(false),
            )
            ->setWithCompanyRelation(true)
            ->setWithBusinessUnitRelation(true)
            ->setWithCompanyUserRelation(true)
            ->setWithSspAssetRelation(true);
    }
}
