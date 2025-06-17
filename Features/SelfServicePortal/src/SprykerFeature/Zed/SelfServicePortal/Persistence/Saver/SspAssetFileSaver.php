<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFile;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;

class SspAssetFileSaver implements FileAttachmentSaverInterface
{
    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery $sspAssetFileQuery
     */
    public function __construct(protected SpySspAssetFileQuery $sspAssetFileQuery)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return bool
     */
    public function isApplicable(FileAttachmentTransfer $fileAttachmentTransfer): bool
    {
        return $fileAttachmentTransfer->getEntityNameOrFail() === SharedSelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function save(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        $sspAssetFileEntity = $this->sspAssetFileQuery
        ->filterByIdSspAssetFile($fileAttachmentTransfer->getEntityIdOrFail())
        ->findOne();

        if ($sspAssetFileEntity === null) {
            $sspAssetFileEntity = new SpySspAssetFile();
        }

        $sspAssetFileEntity
        ->setFkSspAsset($fileAttachmentTransfer->getEntityIdOrFail())
        ->setFkFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail());

        $sspAssetFileEntity->save();

        $fileAttachmentTransfer->setEntityId($sspAssetFileEntity->getIdSspAssetFile());

        return $fileAttachmentTransfer;
    }
}
