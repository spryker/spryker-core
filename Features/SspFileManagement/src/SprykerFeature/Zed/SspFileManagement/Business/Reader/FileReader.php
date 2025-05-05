<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Business\Reader;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface;

class FileReader implements FileReaderInterface
{
    use PermissionAwareTrait;

    /**
     * @param \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface $sspFileManagementRepository
     * @param array<\SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface> $queryStrategies
     */
    public function __construct(
        protected SspFileManagementRepositoryInterface $sspFileManagementRepository,
        protected array $queryStrategies
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    public function getFileAttachmentFileCollectionAccordingToPermissions(
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): FileAttachmentFileCollectionTransfer {
        $emptyFileAttachmentCollectionTransfer = (new FileAttachmentFileCollectionTransfer())->setPagination(new PaginationTransfer());

        $idCompanyUser = $fileAttachmentFileCriteriaTransfer->getCompanyUserOrFail()->getIdCompanyUser();

        if (!$idCompanyUser) {
            return $emptyFileAttachmentCollectionTransfer;
        }

        $activeStrategies = [];
        foreach ($this->queryStrategies as $strategy) {
            if ($this->can($strategy->getPermissionKey(), $idCompanyUser) && $strategy->isApplicable($fileAttachmentFileCriteriaTransfer)) {
                $activeStrategies[] = $strategy;
            }
        }

        if (!$activeStrategies) {
            return $emptyFileAttachmentCollectionTransfer;
        }

        return $this->sspFileManagementRepository->getFileAttachmentFileCollectionAccordingToPermissions(
            $fileAttachmentFileCriteriaTransfer,
            $activeStrategies,
        );
    }
}
