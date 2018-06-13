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
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function mapFileEntityToTransfer(SpyFile $file, FileTransfer $fileTransfer)
    {
        $fileTransfer->fromArray($file->toArray(), true);
        $this->addFileInfoTransfers($file, $fileTransfer);
        $this->addSpyFileLocalizedAttributessTransfers($file, $fileTransfer);

        return $fileTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\Base\SpyMimeType $mimeType
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function mapMimeTypeEntityToTransfer(SpyMimeType $mimeType, MimeTypeTransfer $mimeTypeTransfer)
    {
        return $mimeTypeTransfer
            ->fromArray($mimeType->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyMimeType $mimeType
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyMimeType
     */
    public function mapMimeTypeTransferToEntity(MimeTypeTransfer $mimeTypeTransfer, SpyMimeType $mimeType)
    {
        $mimeType->fromArray($mimeTypeTransfer->modifiedToArray());

        return $mimeType;
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
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return void
     */
    protected function addFileInfoTransfers(SpyFile $file, FileTransfer $fileTransfer)
    {
        foreach ($file->getSpyFileInfos() as $fileInfo) {
            $fileTransfer->addFileInfo(
                (new FileInfoTransfer())->fromArray(
                    $fileInfo->toArray(),
                    true
                )
            );
        }
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return void
     */
    protected function addSpyFileLocalizedAttributessTransfers(SpyFile $file, FileTransfer $fileTransfer)
    {
        $attributesCollection = $file->getSpyFileLocalizedAttributess();

        foreach ($attributesCollection as $attribute) {
            $fileTransfer->addLocalizedAttributes(
                $this->mapFileLocalizedAttributesEntityToTransfer($attribute)
            );
        }
    }
}
