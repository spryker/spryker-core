<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Business\Deleter;

use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementEntityManagerInterface;

class FileAttachmentDeleter implements FileAttachmentDeleterInterface
{
    use TransactionTrait;

    /**
     * @param \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementEntityManagerInterface $entityManager
     */
    public function __construct(protected SspFileManagementEntityManagerInterface $entityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function deleteFileAttachmentCollection(
        FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
    ): FileAttachmentCollectionResponseTransfer {
        $fileAttachmentCollectionResponseTransfer = new FileAttachmentCollectionResponseTransfer();

        $this->getTransactionHandler()->handleTransaction(function () use ($fileAttachmentCollectionDeleteCriteriaTransfer): void {
            $this->executeDeleteFileAttachmentCollectionTransaction(
                $fileAttachmentCollectionDeleteCriteriaTransfer,
            );
        });

        return $fileAttachmentCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    protected function executeDeleteFileAttachmentCollectionTransaction(
        FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
    ): void {
        $this->entityManager->deleteFileAttachmentCollection($fileAttachmentCollectionDeleteCriteriaTransfer);
    }
}
