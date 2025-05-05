<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\AttachFileForm;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\SspAssetAttachmentForm;

class FileAttachmentMapper implements FileAttachmentMapperInterface
{
    /**
     * @var array<string, string>
     */
    public const ENTITY_TO_FORM_KEY_MAP = [
        SspFileManagementConfig::ENTITY_TYPE_COMPANY => AttachFileForm::FIELD_COMPANY_IDS,
        SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER => AttachFileForm::FIELD_COMPANY_USER_IDS,
        SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS,
        SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET => SspAssetAttachmentForm::FIELD_ASSET_IDS,
    ];

    /**
     * @var array<string, string>
     */
    public const FORM_KEY_TO_ENTITY_MAP = [
        AttachFileForm::FIELD_COMPANY_IDS => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        AttachFileForm::FIELD_COMPANY_USER_IDS => SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER,
        AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS => SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
        SspAssetAttachmentForm::FIELD_ASSET_IDS => SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET,
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
                $fileAttachmentCollectionRequestTransfer->addFileAttachmentToAdd($attachment);
            }
        }

        $this->addAttachmentsToDelete($fileAttachmentCollectionRequestTransfer, $currentFileAttachmentCollectionTransfer, $formData);

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
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer
     * @param array<string, mixed> $businessFormData
     *
     * @return void
     */
    protected function addAttachmentsToDelete(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer,
        FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer,
        array $businessFormData
    ): void {
        $selectedEntityIds = [];
        foreach (static::FORM_KEY_TO_ENTITY_MAP as $formKey => $entityType) {
            if ($businessFormData[$formKey] === null) {
                continue;
            }

            $selectedEntityIds[$entityType] = array_flip($businessFormData[$formKey]);
        }

        foreach ($currentFileAttachmentCollectionTransfer->getFileAttachments() as $existingAttachment) {
            $entityType = $existingAttachment->getEntityNameOrFail();
            $entityId = $existingAttachment->getEntityIdOrFail();

            if (!array_key_exists($entityType, $selectedEntityIds)) {
                continue;
            }

            if (!isset($selectedEntityIds[$entityType][$entityId])) {
                $fileAttachmentCollectionRequestTransfer->addFileAttachmentToRemove($existingAttachment);
            }
        }
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
