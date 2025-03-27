<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Business\DashboardDataProvider;

use Generated\Shared\Transfer\DashboardComponentFilesTransfer;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentFileConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileSearchConditionsTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerFeature\Zed\SspFileManagement\Business\Reader\FileReaderInterface;
use SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig;

class FileDashboardDataProvider implements FileDashboardDataProviderInterface
{
    /**
     * @var string
     */
    protected const SORT_CREATED_AT = 'createdAt';

    /**
     * @var bool
     */
    protected const SORT_IS_ASCENDING = false;

    /**
     * @var int
     */
    protected const PAGE_NUMBER = 1;

    /**
     * @var int
     */
    protected const PAGE_MAX_PER_PAGE = 3;

    /**
     * @param \SprykerFeature\Zed\SspFileManagement\Business\Reader\FileReaderInterface $fileReader
     * @param \SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig $fileManagementConfig
     */
    public function __construct(
        protected FileReaderInterface $fileReader,
        protected SspFileManagementConfig $fileManagementConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DashboardResponseTransfer $dashboardResponseTransfer
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function provideDashboardData(
        DashboardResponseTransfer $dashboardResponseTransfer,
        DashboardRequestTransfer $dashboardRequestTransfer
    ): DashboardResponseTransfer {
        $fileAttachmentFileCriteriaTransfer = (new FileAttachmentFileCriteriaTransfer())
            ->setFileAttachmentFileSearchConditions(
                (new FileAttachmentFileSearchConditionsTransfer()),
            )
            ->setFileAttachmentFileConditions(
                (new FileAttachmentFileConditionsTransfer()),
            )
            ->setPagination(
                (new PaginationTransfer())
                    ->setMaxPerPage(static::PAGE_MAX_PER_PAGE)
                    ->setPage(static::PAGE_NUMBER),
            )
            ->setCompanyUser($dashboardRequestTransfer->getCompanyUser())
            ->addSort(
                (new SortTransfer())
                    ->setField(static::SORT_CREATED_AT)
                    ->setIsAscending(static::SORT_IS_ASCENDING),
            );

        $fileAttachmentFileCollectionTransfer = $this->fileReader->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);

        $dashboardComponentFilesTransfer = (new DashboardComponentFilesTransfer())
            ->setFileAttachmentFileCollection($fileAttachmentFileCollectionTransfer);

        return $dashboardResponseTransfer->setDashboardComponentFiles($dashboardComponentFilesTransfer);
    }
}
