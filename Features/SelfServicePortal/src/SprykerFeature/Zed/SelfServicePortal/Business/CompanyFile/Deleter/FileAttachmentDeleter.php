<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Deleter;

use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;
use Generated\Shared\Transfer\FileCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class FileAttachmentDeleter implements FileAttachmentDeleterInterface
{
    use TransactionTrait;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReaderInterface $companyFileReader
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface $entityManager
     */
    public function __construct(
        protected CompanyFileReaderInterface $companyFileReader,
        protected SelfServicePortalEntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractFileIds(FileCollectionTransfer $fileCollectionTransfer): array
    {
        $fileIds = [];

        foreach ($fileCollectionTransfer->getFiles() as $fileTransfer) {
            $fileIds[] = $fileTransfer->getIdFileOrFail();
        }

        return $fileIds;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function deleteFileAttachmentCollection(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): FileAttachmentCollectionResponseTransfer {
        $this->getTransactionHandler()->handleTransaction(function () use ($fileAttachmentCollectionRequestTransfer): void {
            foreach ($fileAttachmentCollectionRequestTransfer->getFileAttachmentsToDelete() as $fileAttachmentToDelete) {
                $this->entityManager->deleteFileAttachmentCollection($fileAttachmentToDelete);
            }
        });

        return new FileAttachmentCollectionResponseTransfer();
    }
}
