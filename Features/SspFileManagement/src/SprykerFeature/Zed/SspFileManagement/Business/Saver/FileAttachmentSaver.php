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
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
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

        $resultCollection = new FileAttachmentCollectionTransfer();
        $resultCollection->setFileAttachments($fileAttachmentCollectionRequestTransfer->getFileAttachments());

        return (new FileAttachmentCollectionResponseTransfer())
            ->setFileAttachments($resultCollection->getFileAttachments());
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     *
     * @return void
     */
    public function executeSaveFileAttachmentCollectionTransaction(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): void {
        $idFile = $fileAttachmentCollectionRequestTransfer->getIdFileOrFail();
        $newCollectionMap = $this->createNewCollectionMap($fileAttachmentCollectionRequestTransfer->getFileAttachments());
        $existingCollectionMap = $this->getExistingCollectionMap($idFile);

        $entitiesToDelete = $this->findEntitiesToDelete($newCollectionMap, $existingCollectionMap);
        $deleteCriteria = $this->createDeleteCriteria($entitiesToDelete);

        $this->entityManager->deleteFileAttachmentCollection($deleteCriteria);
        $this->saveEntitiesToCreate($newCollectionMap, $existingCollectionMap);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\FileAttachmentTransfer> $fileAttachments
     *
     * @return array<string, array<int, \Generated\Shared\Transfer\FileAttachmentTransfer>>
     */
    protected function createNewCollectionMap(ArrayObject $fileAttachments): array
    {
        $collectionMap = [];

        foreach ($fileAttachments as $fileAttachmentTransfer) {
            $entityName = $fileAttachmentTransfer->getEntityNameOrFail();
            $entityId = $fileAttachmentTransfer->getEntityIdOrFail();
            $collectionMap[$entityName][$entityId] = $fileAttachmentTransfer;
        }

        return $collectionMap;
    }

    /**
     * @param int $idFile
     *
     * @return array<string, array<int, \Generated\Shared\Transfer\FileAttachmentTransfer>>
     */
    protected function getExistingCollectionMap(int $idFile): array
    {
        $fileAttachmentCriteriaTransfer = (new FileAttachmentCriteriaTransfer())
            ->setFileAttachmentConditions(
                (new FileAttachmentConditionsTransfer())->addIdFile($idFile),
            );

        $existingCollection = $this->sspFileManagementRepository->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);

        $existingCollectionMap = [];
        foreach ($existingCollection->getFileAttachments() as $fileAttachmentTransfer) {
            $entityName = $fileAttachmentTransfer->getEntityNameOrFail();
            $entityId = $fileAttachmentTransfer->getEntityIdOrFail();
            $existingCollectionMap[$entityName][$entityId] = $fileAttachmentTransfer;
        }

        return $existingCollectionMap;
    }

    /**
     * @param array<string, array<int, \Generated\Shared\Transfer\FileAttachmentTransfer>> $newCollectionMap
     * @param array<string, array<int, \Generated\Shared\Transfer\FileAttachmentTransfer>> $existingCollectionMap
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\FileAttachmentTransfer>
     */
    protected function findEntitiesToDelete(
        array $newCollectionMap,
        array $existingCollectionMap
    ): ArrayObject {
        $collectionToDelete = new ArrayObject();

        if (!$newCollectionMap) {
            foreach ($existingCollectionMap as $entityCollection) {
                foreach ($entityCollection as $fileAttachmentTransfer) {
                    $collectionToDelete->append($fileAttachmentTransfer);
                }
            }

            return $collectionToDelete;
        }

        foreach ($existingCollectionMap as $entityName => $existingEntities) {
            $newEntities = $newCollectionMap[$entityName] ?? [];
            $diffEntities = array_udiff(
                $existingEntities,
                $newEntities,
                [$this, 'compareFileAttachments'],
            );

            foreach ($diffEntities as $fileAttachmentTransfer) {
                $collectionToDelete->append($fileAttachmentTransfer);
            }
        }

        return $collectionToDelete;
    }

    /**
     * @param array<string, array<int, \Generated\Shared\Transfer\FileAttachmentTransfer>> $newCollectionMap
     * @param array<string, array<int, \Generated\Shared\Transfer\FileAttachmentTransfer>> $existingCollectionMap
     *
     * @return void
     */
    protected function saveEntitiesToCreate(
        array $newCollectionMap,
        array $existingCollectionMap
    ): void {
        foreach ($newCollectionMap as $entityName => $newEntities) {
            $existingEntities = $existingCollectionMap[$entityName] ?? [];
            $newEntities = array_udiff(
                $newEntities,
                $existingEntities,
                [$this, 'compareFileAttachments'],
            );

            foreach ($newEntities as $fileAttachmentTransfer) {
                $this->entityManager->saveFileAttachment($fileAttachmentTransfer);
            }
        }
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
