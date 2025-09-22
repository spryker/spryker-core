<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelToFileQuery;

class FileAttachmentSaver
{
    public function __construct(
        protected SpyCompanyBusinessUnitFileQuery $companyBusinessUnitFileQuery,
        protected SpyCompanyUserFileQuery $companyUserFileQuery,
        protected SpySspAssetFileQuery $sspAssetFileQuery,
        protected SpySspModelToFileQuery $sspModelToFileQuery
    ) {
    }

    public function saveBusinessUnitFileAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        if (!$fileAttachmentTransfer->getBusinessUnitCollection()?->getCompanyBusinessUnits()) {
            return $fileAttachmentTransfer;
        }

        foreach ($fileAttachmentTransfer->getBusinessUnitCollection()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitFileEntity = $this->companyBusinessUnitFileQuery->clear()
                ->filterByFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail())
                ->filterByFkFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail())
                ->findOneOrCreate();

            if ($companyBusinessUnitFileEntity->isNew() || $companyBusinessUnitFileEntity->isModified()) {
                $companyBusinessUnitFileEntity->save();
            }
        }

        return $fileAttachmentTransfer;
    }

    public function saveCompanyUserAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        if (!$fileAttachmentTransfer->getCompanyUserCollection()?->getCompanyUsers()) {
            return $fileAttachmentTransfer;
        }

        foreach ($fileAttachmentTransfer->getCompanyUserCollection()->getCompanyUsers() as $companyUserTransfer) {
            $companyUserFileEntity = $this->companyUserFileQuery->clear()
                ->filterByFkCompanyUser($companyUserTransfer->getIdCompanyUserOrFail())
                ->filterByFkFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail())
                ->findOneOrCreate();

            if ($companyUserFileEntity->isNew() || $companyUserFileEntity->isModified()) {
                $companyUserFileEntity->save();
            }
        }

        return $fileAttachmentTransfer;
    }

    public function saveSspAssetFileAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        if (!$fileAttachmentTransfer->getSspAssetCollection()?->getSspAssets()) {
            return $fileAttachmentTransfer;
        }

        foreach ($fileAttachmentTransfer->getSspAssetCollection()->getSspAssets() as $sspAssetTransfer) {
            $sspAssetFileEntity = $this->sspAssetFileQuery->clear()
                ->filterByFkSspAsset($sspAssetTransfer->getIdSspAssetOrFail())
                ->filterByFkFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail())
                ->findOneOrCreate();

            if ($sspAssetFileEntity->isNew() || $sspAssetFileEntity->isModified()) {
                $sspAssetFileEntity->save();
            }
        }

        return $fileAttachmentTransfer;
    }

    public function saveSspModelToFileAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        if (!$fileAttachmentTransfer->getSspModelCollection()?->getSspModels()) {
            return $fileAttachmentTransfer;
        }

        foreach ($fileAttachmentTransfer->getSspModelCollection()->getSspModels() as $sspModelTransfer) {
            $sspModelToFileEntity = $this->sspModelToFileQuery->clear()
                ->filterByFkSspModel($sspModelTransfer->getIdSspModelOrFail())
                ->filterByFkFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail())
                ->findOneOrCreate();

            if ($sspModelToFileEntity->isNew() || $sspModelToFileEntity->isModified()) {
                $sspModelToFileEntity->save();
            }
        }

        return $fileAttachmentTransfer;
    }
}
