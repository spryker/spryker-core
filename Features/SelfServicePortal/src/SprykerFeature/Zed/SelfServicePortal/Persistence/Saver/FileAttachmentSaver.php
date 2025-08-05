<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery;

class FileAttachmentSaver
{
    public function __construct(
        protected SpyCompanyFileQuery $companyFileQuery,
        protected SpyCompanyBusinessUnitFileQuery $companyBusinessUnitFileQuery,
        protected SpyCompanyUserFileQuery $companyUserFileQuery,
        protected SpySspAssetFileQuery $sspAssetFileQuery
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

    public function saveCompanyFileAttachments(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        if (!$fileAttachmentTransfer->getCompanyCollection()?->getCompanies()) {
            return $fileAttachmentTransfer;
        }

        foreach ($fileAttachmentTransfer->getCompanyCollection()->getCompanies() as $companyTransfer) {
            $companyFileEntity = $this->companyFileQuery->clear()
                ->filterByFkCompany($companyTransfer->getIdCompany())
                ->filterByFkFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail())
                ->findOneOrCreate();

            if ($companyFileEntity->isNew() || $companyFileEntity->isModified()) {
                $companyFileEntity->save();
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
}
