<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Creator;

use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory getFactory()
 */
class FileAttachmentCreator implements FileAttachmentCreatorInterface
{
    use TransactionTrait;

    public function __construct(
        protected SelfServicePortalEntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function createFileAttachmentCollection(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): FileAttachmentCollectionResponseTransfer {
        $fileAttachments = $fileAttachmentCollectionRequestTransfer->getFileAttachmentsToAdd();

        $this->getTransactionHandler()->handleTransaction(function () use ($fileAttachments): void {
            foreach ($fileAttachments as $fileAttachmentTransfer) {
                $this->entityManager->saveFileAttachment($fileAttachmentTransfer);
            }
        });

        return (new FileAttachmentCollectionResponseTransfer())->setFileAttachments($fileAttachments);
    }
}
