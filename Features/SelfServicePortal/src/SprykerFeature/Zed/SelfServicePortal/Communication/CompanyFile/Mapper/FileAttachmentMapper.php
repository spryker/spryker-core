<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\SspAssetAttachmentForm;

class FileAttachmentMapper implements FileAttachmentMapperInterface
{
    /**
     * @var array<string, string>
     */
    public const ENTITY_TO_FORM_KEY_MAP = [
        SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY => AttachFileForm::FIELD_COMPANY_IDS,
        SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER => AttachFileForm::FIELD_COMPANY_USER_IDS,
        SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS,
        SharedSelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET => SspAssetAttachmentForm::FIELD_ASSET_IDS,
    ];

    /**
     * @var array<string, string>
     */
    public const FORM_KEY_TO_ENTITY_MAP = [
        AttachFileForm::FIELD_COMPANY_IDS => SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY,
        AttachFileForm::FIELD_COMPANY_USER_IDS => SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER,
        AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS => SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
        SspAssetAttachmentForm::FIELD_ASSET_IDS => SharedSelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET,
    ];

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer
     * @param array<string, mixed> $formData
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer
     */
    public function mapFormDataToFileAttachmentCollectionTransfer(
        FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer,
        array $formData,
        int $idFile
    ): FileAttachmentCollectionRequestTransfer {
        $fileAttachmentCollectionRequestTransfer = (new FileAttachmentCollectionRequestTransfer())->setIdFile($idFile);

        $submittedFileAttachments = new ArrayObject();

        foreach (static::FORM_KEY_TO_ENTITY_MAP as $formKey => $entityName) {
            if ($formData[$formKey] === null) {
                continue;
            }
            foreach ($formData[$formKey] as $entityId) {
                $submittedFileAttachments->append((new FileAttachmentTransfer())
                    ->setEntityId($entityId)
                    ->setEntityName($entityName)
                    ->setFile((new FileTransfer())->setIdFile($idFile)));
            }
        }

        $existingAttachmentIds = $this->getExistingAttachmentIds($currentFileAttachmentCollectionTransfer);

        foreach ($submittedFileAttachments as $attachment) {
            if (!isset($existingAttachmentIds[$this->getAttachmentIdentifier($attachment)])) {
                $fileAttachmentCollectionRequestTransfer->addFileAttachment($attachment);
            }
        }

        return $fileAttachmentCollectionRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer
     *
     * @return array<string, array<int>>
     */
    public function mapFileAttachmentCollectionTransferToFormData(FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer): array
    {
        $formData = [
            AttachFileForm::FIELD_COMPANY_IDS => [],
            AttachFileForm::FIELD_COMPANY_USER_IDS => [],
            AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS => [],
            SspAssetAttachmentForm::FIELD_ASSET_IDS => [],
        ];

        foreach ($fileAttachmentCollectionTransfer->getFileAttachments() as $fileAttachmentTransfer) {
            $entityName = $fileAttachmentTransfer->getEntityNameOrFail();
            if (isset(static::ENTITY_TO_FORM_KEY_MAP[$entityName])) {
                $formKey = static::ENTITY_TO_FORM_KEY_MAP[$entityName];
                $formData[$formKey][] = $fileAttachmentTransfer->getEntityIdOrFail();
            }
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer
     * @param array<string, mixed> $businessFormData
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer|null
     */
    public function mapFormDataToFileAttachmentCollectionDeleteCriteriaTransfer(
        FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer,
        array $businessFormData
    ): ?FileAttachmentCollectionDeleteCriteriaTransfer {
        $selectedEntityIds = [];
        foreach (static::FORM_KEY_TO_ENTITY_MAP as $formKey => $entityType) {
            if ($businessFormData[$formKey] === null) {
                continue;
            }

            $selectedEntityIds[$entityType] = array_flip($businessFormData[$formKey]);
        }

        $fileAttachmentsToRemove = [];

        foreach ($currentFileAttachmentCollectionTransfer->getFileAttachments() as $existingAttachment) {
            $entityType = $existingAttachment->getEntityNameOrFail();
            $entityId = $existingAttachment->getEntityIdOrFail();

            if (!array_key_exists($entityType, $selectedEntityIds)) {
                continue;
            }

            if (!isset($selectedEntityIds[$entityType][$entityId])) {
                $fileAttachmentsToRemove[] = $existingAttachment;
            }
        }

        if (!$fileAttachmentsToRemove) {
            return null;
        }

        return $this->createDeleteCriteria($fileAttachmentsToRemove);
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\FileAttachmentTransfer> $collectionToDelete
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer
     */
    protected function createDeleteCriteria(
        array $collectionToDelete
    ): FileAttachmentCollectionDeleteCriteriaTransfer {
        $deleteCriteria = new FileAttachmentCollectionDeleteCriteriaTransfer();

        $entityMethodMap = [
            SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY => 'addIdCompany',
            SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER => 'addIdCompanyUser',
            SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => 'addIdCompanyBusinessUnit',
            SharedSelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET => 'addIdSspAsset',
        ];

        foreach ($collectionToDelete as $fileAttachmentTransfer) {
            $entityName = $fileAttachmentTransfer->getEntityNameOrFail();

            if (isset($entityMethodMap[$entityName])) {
                $method = $entityMethodMap[$entityName];
                $deleteCriteria->$method($fileAttachmentTransfer->getEntityIdOrFail());
                $deleteCriteria->addIdFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail());

                continue;
            }

            if (!isset($entityMethodMap[$entityName]) && $fileAttachmentTransfer->getFile() && $fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail()) {
                $deleteCriteria->addIdFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail());
            }
        }

        return $deleteCriteria;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return string
     */
    protected function getAttachmentIdentifier(FileAttachmentTransfer $fileAttachmentTransfer): string
    {
        return sprintf(
            '%s_%s',
            $fileAttachmentTransfer->getEntityNameOrFail(),
            $fileAttachmentTransfer->getEntityIdOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer
     *
     * @return array<string, bool>
     */
    protected function getExistingAttachmentIds(FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer): array
    {
        $existingAttachmentIds = [];

        foreach ($fileAttachmentCollectionTransfer->getFileAttachments() as $existingAttachment) {
            $existingAttachmentIds[$this->getAttachmentIdentifier($existingAttachment)] = true;
        }

        return $existingAttachmentIds;
    }
}
