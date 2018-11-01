<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Generated\Shared\Transfer\SpyFileEntityTransfer;
use Generated\Shared\Transfer\SpyFileInfoEntityTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
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
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    public function mapFileTransferToEntity(FileTransfer $fileTransfer, SpyFile $file)
    {
        $file->fromArray($fileTransfer->toArray());
        $fileInfoCollection = $file->getSpyFileInfos()->toKeyIndex();
        $fileInfoTransferCollection = $fileTransfer->getFileInfo();

        foreach ($fileInfoTransferCollection as $fileInfoTransfer) {
            $fileInfo = $fileInfoCollection[$fileInfoTransfer->getIdFileInfo()] ?? new SpyFileInfo();
            $fileInfo = $this->mapFileInfoTransferToEntity($fileInfoTransfer, $fileInfo);

            if ($fileInfo->isNew()) {
                $file->addSpyFileInfo($fileInfo);
            }
        }

        return $file;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     * @param \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    public function mapFileInfoEntityToTransfer(SpyFileInfo $fileInfo, FileInfoTransfer $fileInfoTransfer)
    {
        return $fileInfoTransfer->fromArray($fileInfo->toArray());
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     * @param \Generated\Shared\Transfer\SpyFileInfoEntityTransfer $fileInfoEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyFileInfoEntityTransfer
     */
    public function mapFileInfoEntityToEntityTransfer(SpyFileInfo $fileInfo, SpyFileInfoEntityTransfer $fileInfoEntityTransfer)
    {
        $fileInfoEntityTransfer->fromArray($fileInfo->toArray(), true);
        $fileInfoEntityTransfer->setFile(
            (new SpyFileEntityTransfer())->fromArray($fileInfo->getFile()->toArray())
        );

        return $fileInfoEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    public function mapFileInfoTransferToEntity(FileInfoTransfer $fileInfoTransfer, SpyFileInfo $fileInfo)
    {
        $fileInfo->fromArray($fileInfoTransfer->toArray());

        return $fileInfo;
    }

    /**
     * @param \Generated\Shared\Transfer\FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributes $fileLocalizedAttributes
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributes
     */
    public function mapFileLocalizedAttributesTransferToEntity(FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer, SpyFileLocalizedAttributes $fileLocalizedAttributes)
    {
        $fileLocalizedAttributes->fromArray(
            $fileLocalizedAttributesTransfer->toArray()
        );

        return $fileLocalizedAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectory
     */
    public function mapFileDirectoryTransferToEntity(FileDirectoryTransfer $fileDirectoryTransfer, SpyFileDirectory $fileDirectory)
    {
        $fileDirectory->fromArray($fileDirectoryTransfer->toArray());

        return $fileDirectory;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    public function mapFileDirectoryEntityToTransfer(SpyFileDirectory $fileDirectory, FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fileDirectoryTransfer->fromArray($fileDirectory->toArray());

        foreach ($fileDirectory->getSpyFileDirectoryLocalizedAttributess() as $fileDirectoryLocalizedAttributes) {
            $fileDirectoryLocalizedAttributesTransfer = new FileDirectoryLocalizedAttributesTransfer();
            $fileDirectoryLocalizedAttributesTransfer->fromArray(
                $fileDirectoryLocalizedAttributes->toArray(),
                true
            );
            $fileDirectoryTransfer->addFileDirectoryLocalizedAttribute($fileDirectoryLocalizedAttributesTransfer);
        }

        return $fileDirectoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer $fileDirectoryLocalizedAttributesTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes $fileDirectoryLocalizedAttributes
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes
     */
    public function mapFileDirectoryLocalizedAttributesTransferToEntity(FileDirectoryLocalizedAttributesTransfer $fileDirectoryLocalizedAttributesTransfer, SpyFileDirectoryLocalizedAttributes $fileDirectoryLocalizedAttributes)
    {
        $fileDirectoryLocalizedAttributes->fromArray($fileDirectoryLocalizedAttributesTransfer->toArray());

        return $fileDirectoryLocalizedAttributes;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyMimeType $mimeType
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function mapMimeTypeEntityToTransfer(SpyMimeType $mimeType, MimeTypeTransfer $mimeTypeTransfer)
    {
        return $mimeTypeTransfer->fromArray($mimeType->toArray(), true);
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
        $fileTransfer->setLocalizedAttributes(new ArrayObject());
        $attributesCollection = $file->getSpyFileLocalizedAttributess();

        foreach ($attributesCollection as $attribute) {
            $fileTransfer->addLocalizedAttributes(
                $this->mapFileLocalizedAttributesEntityToTransfer($attribute)
            );
        }
    }
}
