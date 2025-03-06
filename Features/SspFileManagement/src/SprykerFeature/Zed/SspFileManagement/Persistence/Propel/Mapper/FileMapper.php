<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Propel\Runtime\Collection\ObjectCollection;

class FileMapper
{
 /**
  * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\FileManager\Persistence\SpyFile> $fileEntities
  * @param \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer $fileCollectionTransfer
  *
  * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
  */
    public function mapEntityCollectionToTransferCollection(
        ObjectCollection $fileEntities,
        FileAttachmentFileCollectionTransfer $fileCollectionTransfer
    ): FileAttachmentFileCollectionTransfer {
        foreach ($fileEntities as $fileEntity) {
            $fileTransfer = $this->mapEntityToTransfer($fileEntity);
            $fileTransfer = $this->addFileInfoTransfers($fileEntity, $fileTransfer);

            $fileCollectionTransfer->addFile($fileTransfer);
        }

        return $fileCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function mapEntityToTransfer(SpyFile $fileEntity): FileTransfer
    {
        return (new FileTransfer())
            ->fromArray($fileEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function addFileInfoTransfers(SpyFile $file, FileTransfer $fileTransfer): FileTransfer
    {
        foreach ($file->getSpyFileInfos() as $fileInfo) {
            $fileTransfer->addFileInfo(
                (new FileInfoTransfer())->fromArray(
                    $fileInfo->toArray(),
                    true,
                ),
            );
        }

        return $fileTransfer;
    }
}
