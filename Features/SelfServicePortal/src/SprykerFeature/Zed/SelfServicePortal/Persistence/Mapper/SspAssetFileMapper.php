<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFile;
use Propel\Runtime\Collection\ObjectCollection;

class SspAssetFileMapper
{
    /**
     * @var string
     */
    protected const ENTITY_NAME = 'ssp_asset';

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFile> $sspAssetFileEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\FileAttachmentTransfer>
     */
    public function mapSspAssetFileEntitiesToFileAttachmentTransfers(ObjectCollection $sspAssetFileEntityCollection): array
    {
        $fileAttachmentTransfers = [];

        foreach ($sspAssetFileEntityCollection as $sspAssetFileEntity) {
            $fileAttachmentTransfers[] = $this->mapSspAssetFileEntityToFileAttachmentTransfer($sspAssetFileEntity);
        }

        return $fileAttachmentTransfers;
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFile $sspAssetFileEntity
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    protected function mapSspAssetFileEntityToFileAttachmentTransfer(
        SpySspAssetFile $sspAssetFileEntity
    ): FileAttachmentTransfer {
        $fileTransfer = (new FileTransfer())->setIdFile($sspAssetFileEntity->getFkFile());

        return (new FileAttachmentTransfer())
            ->setEntityId($sspAssetFileEntity->getFkSspAsset())
            ->setFile($fileTransfer)
            ->setEntityName(static::ENTITY_NAME);
    }
}
