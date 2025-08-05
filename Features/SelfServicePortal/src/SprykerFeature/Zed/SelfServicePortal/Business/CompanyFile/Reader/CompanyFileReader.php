<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader;

use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentPermissionCheckerInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentPermissionExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class CompanyFileReader implements CompanyFileReaderInterface
{
    use PermissionAwareTrait;

    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
        protected FileAttachmentPermissionCheckerInterface $fileAttachmentPermissionChecker,
        protected FileAttachmentPermissionExpanderInterface $fileAttachmentPermissionExpander
    ) {
    }

    public function getFileAttachmentCollection(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCollectionTransfer {
        if (!$this->fileAttachmentPermissionChecker->isCompanyUserGrantedToApplyCriteria($fileAttachmentCriteriaTransfer)) {
            return (new FileAttachmentCollectionTransfer())->setPagination(
                $fileAttachmentCriteriaTransfer->getPagination(),
            );
        }

        $this->fileAttachmentPermissionExpander->expand($fileAttachmentCriteriaTransfer);

        return $this->selfServicePortalRepository->getFileAttachmentCollection(
            $fileAttachmentCriteriaTransfer,
        );
    }
}
