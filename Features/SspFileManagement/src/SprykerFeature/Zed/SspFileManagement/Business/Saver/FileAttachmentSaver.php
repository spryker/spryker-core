<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Business\Saver;

use ArrayObject;
use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;
use SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementEntityManagerInterface;
use SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface;

class FileAttachmentSaver implements FileAttachmentSaverInterface
{
    use TransactionTrait;

    /**
     * @param \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementEntityManagerInterface $entityManager
     * @param \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface $sspFileManagementRepository
     */
    public function __construct(
        protected SspFileManagementEntityManagerInterface $entityManager,
        protected SspFileManagementRepositoryInterface $sspFileManagementRepository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function saveFileAttachmentCollection(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): FileAttachmentCollectionResponseTransfer {
        $this->getTransactionHandler()->handleTransaction(function () use ($fileAttachmentCollectionRequestTransfer): void {
            $this->executeSaveFileAttachmentCollectionTransaction($fileAttachmentCollectionRequestTransfer);
        });

        return (new FileAttachmentCollectionResponseTransfer())->setFileAttachments($fileAttachmentCollectionRequestTransfer->getFileAttachmentsToAdd());
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     *
     * @return void
     */
    public function executeSaveFileAttachmentCollectionTransaction(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): void {
        foreach ($fileAttachmentCollectionRequestTransfer->getFileAttachmentsToAdd() as $fileAttachmentTransfer) {
            $this->entityManager->saveFileAttachment($fileAttachmentTransfer);
        }

        $deleteCriteria = $this->createDeleteCriteria($fileAttachmentCollectionRequestTransfer->getFileAttachmentsToRemove());

        $this->entityManager->deleteFileAttachmentCollection($deleteCriteria);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\FileAttachmentTransfer> $collectionToDelete
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer
     */
    protected function createDeleteCriteria(
        ArrayObject $collectionToDelete
    ): FileAttachmentCollectionDeleteCriteriaTransfer {
        $deleteCriteria = new FileAttachmentCollectionDeleteCriteriaTransfer();

        $entityMethodMap = [
            SspFileManagementConfig::ENTITY_TYPE_COMPANY => 'addIdCompany',
            SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER => 'addIdCompanyUser',
            SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => 'addIdCompanyBusinessUnit',
            SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET => 'addIdSspAsset',
        ];

        foreach ($collectionToDelete as $fileAttachmentTransfer) {
            $entityName = $fileAttachmentTransfer->getEntityNameOrFail();

            if (isset($entityMethodMap[$entityName])) {
                $method = $entityMethodMap[$entityName];
                $deleteCriteria->$method($fileAttachmentTransfer->getEntityIdOrFail());
                $deleteCriteria->addIdFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail());
            }
        }

        return $deleteCriteria;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $a
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $b
     *
     * @return int
     */
    protected function compareFileAttachments(
        FileAttachmentTransfer $a,
        FileAttachmentTransfer $b
    ): int {
        return $a->getEntityId() <=> $b->getEntityId();
    }
}
