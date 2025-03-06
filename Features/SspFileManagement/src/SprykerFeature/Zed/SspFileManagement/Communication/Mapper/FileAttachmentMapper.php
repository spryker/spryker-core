<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Mapper;

use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\AttachFileForm;

class FileAttachmentMapper implements FileAttachmentMapperInterface
{
    /**
     * @var array<string, string>
     */
    protected const ENTITY_TO_FORM_KEY_MAP = [
        SspFileManagementConfig::ENTITY_TYPE_COMPANY => AttachFileForm::FIELD_COMPANY_IDS,
        SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER => AttachFileForm::FIELD_COMPANY_USER_IDS,
        SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS,
    ];

    /**
     * @var array<string, string>
     */
    protected const FORM_KEY_TO_ENTITY_MAP = [
        AttachFileForm::FIELD_COMPANY_IDS => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
        AttachFileForm::FIELD_COMPANY_USER_IDS => SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER,
        AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS => SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
    ];

    /**
     * @param array<string, mixed> $formData
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function mapFormDataToFileAttachmentCollectionTransfer(
        array $formData,
        FileManagerDataTransfer $fileManagerDataTransfer,
        int $idFile
    ): FileAttachmentCollectionTransfer {
        $fileAttachmentCollectionTransfer = new FileAttachmentCollectionTransfer();

        foreach (static::FORM_KEY_TO_ENTITY_MAP as $formKey => $entityName) {
            foreach ($formData[$formKey] as $entityId) {
                $fileAttachmentTransfer = $this->createFileAttachmentTransfer(
                    $entityId,
                    $entityName,
                    $fileManagerDataTransfer,
                    $idFile,
                );
                $fileAttachmentCollectionTransfer->addFileAttachment($fileAttachmentTransfer);
            }
        }

        return $fileAttachmentCollectionTransfer;
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
     * @param int $entityId
     * @param string $entityName
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    protected function createFileAttachmentTransfer(
        int $entityId,
        string $entityName,
        FileManagerDataTransfer $fileManagerDataTransfer,
        int $idFile
    ): FileAttachmentTransfer {
        $fileTransfer = $fileManagerDataTransfer->getFileOrFail();
        $fileTransfer->setIdFile($idFile);

        $fileAttachmentTransfer = new FileAttachmentTransfer();
        $fileAttachmentTransfer->setEntityId($entityId);
        $fileAttachmentTransfer->setEntityName($entityName);
        $fileAttachmentTransfer->setFile($fileTransfer);

        return $fileAttachmentTransfer;
    }
}
