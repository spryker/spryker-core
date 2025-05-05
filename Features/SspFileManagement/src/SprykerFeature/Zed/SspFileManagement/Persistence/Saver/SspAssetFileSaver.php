<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SspFileManagement\Persistence\SpySspAssetFile;
use Orm\Zed\SspFileManagement\Persistence\SpySspAssetFileQuery;

class SspAssetFileSaver implements FileAttachmentSaverInterface
{
    /**
     * @param \Orm\Zed\SspFileManagement\Persistence\SpySspAssetFileQuery $sspAssetFileQuery
     */
    public function __construct(protected SpySspAssetFileQuery $sspAssetFileQuery)
    {
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
