<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence\Mapper;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributes;
use Orm\Zed\FileManager\Persistence\SpyMimeType;

class FileManagerMapper implements FileManagerMapperInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function mapFileEntityToTransfer(SpyFile $fileEntity)
    {
        $fileTransfer = new FileTransfer();
        $fileTransfer->fromArray($fileEntity->toArray(), true);
        $this->addFileInfoTransfers($fileEntity, $fileTransfer);
        $this->addSpyFileLocalizedAttributessTransfers($fileEntity, $fileTransfer);

        return $fileTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\Base\SpyMimeType $mimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function mapMimeTypeEntityToTransfer(SpyMimeType $mimeType)
    {
        return (new MimeTypeTransfer())
            ->fromArray($mimeType->toArray(), true);
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributes $fileLocalizedAttributes
     *
     * @return \Generated\Shared\Transfer\FileLocalizedAttributesTransfer
     */
    protected function mapFileLocalizedAttributesEntityToTransfer(SpyFileLocalizedAttributes $fileLocalizedAttributes)
    {
        return (new FileLocalizedAttributesTransfer())
            ->fromArray($fileLocalizedAttributes->toArray(), true);
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return void
     */
    protected function addFileInfoTransfers(SpyFile $fileEntity, FileTransfer $fileTransfer)
    {
        foreach ($fileEntity->getSpyFileInfos() as $fileInfo) {
            $fileTransfer->addFileInfo(
                (new FileInfoTransfer())->fromArray(
                    $fileInfo->toArray(),
                    true
                )
            );
        }
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return void
     */
    protected function addSpyFileLocalizedAttributessTransfers(SpyFile $fileEntity, FileTransfer $fileTransfer)
    {
        $attributesCollection = $fileEntity->getSpyFileLocalizedAttributess();

        foreach ($attributesCollection as $attribute) {
            $fileTransfer->addLocalizedAttributes(
                $this->mapFileLocalizedAttributesEntityToTransfer($attribute)
            );
        }
    }
}
