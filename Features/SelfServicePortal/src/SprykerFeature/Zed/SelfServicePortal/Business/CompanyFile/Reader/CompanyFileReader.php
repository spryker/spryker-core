<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader;

use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class CompanyFileReader implements CompanyFileReaderInterface
{
    use PermissionAwareTrait;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     * @param list<\SprykerFeature\Zed\SelfServicePortal\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface> $queryStrategies
     */
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
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

        return $this->selfServicePortalRepository->getFileAttachmentFileCollectionAccordingToPermissions(
            $fileAttachmentFileCriteriaTransfer,
            $activeStrategies,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollection(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): FileAttachmentCollectionTransfer
    {
        return $this->selfServicePortalRepository->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);
    }
}
