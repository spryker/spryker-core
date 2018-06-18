<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerPersistenceFactory getFactory()
 */
class FileManagerEntityManager extends AbstractEntityManager implements FileManagerEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function saveFile(FileTransfer $fileTransfer)
    {
        $fileEntity = $this->getFactory()
            ->createFileQuery()
            ->filterByIdFile($fileTransfer->getIdFile())
            ->findOneOrCreate();

        $fileEntity = $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileTransferToEntity($fileTransfer, $fileEntity);

        $fileEntity->save();

        $fileTransfer = $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileEntityToTransfer($fileEntity, $fileTransfer);

        return $fileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return bool
     */
    public function deleteFile(FileTransfer $fileTransfer)
    {
        $fileEntity = $this->getFactory()
            ->createFileQuery()
            ->filterByIdFile($fileTransfer->getIdFile())
            ->findOne();

        if ($fileEntity === null) {
            return false;
        }

        $fileEntity = $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileTransferToEntity($fileTransfer, $fileEntity);

        $fileEntity->delete();

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    public function saveFileInfo(FileInfoTransfer $fileInfoTransfer)
    {
        $fileInfoEntity = $this->getFactory()
            ->createFileInfoQuery()
            ->filterByIdFileInfo($fileInfoTransfer->getIdFileInfo())
            ->findOneOrCreate();

        $fileInfoEntity = $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileInfoTransferToEntity($fileInfoTransfer, $fileInfoEntity);

        $fileInfoEntity->save();
        $fileInfoTransfer->setIdFileInfo($fileInfoEntity->getIdFileInfo());

        return $fileInfoTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer
     *
     * @return bool
     */
    public function deleteFileInfo(FileInfoTransfer $fileInfoTransfer)
    {
        $fileInfoEntity = $this->getFactory()
            ->createFileInfoQuery()
            ->filterByIdFileInfo($fileInfoTransfer->getIdFileInfo())
            ->findOne();

        if ($fileInfoEntity === null) {
            return false;
        }

        $fileInfoEntity->delete();

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\FileLocalizedAttributesTransfer
     */
    public function saveFileLocalizedAttribute(FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer)
    {
        $fileLocalizedAttributesEntity = $this->getFactory()
            ->createFileLocalizedAttributesQuery()
            ->filterByIdFileLocalizedAttributes($fileLocalizedAttributesTransfer->getIdFileLocalizedAttributes())
            ->findOneOrCreate();

        $fileLocalizedAttributesEntity = $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileLocalizedAttributesTransferToEntity($fileLocalizedAttributesTransfer, $fileLocalizedAttributesEntity);

        $fileLocalizedAttributesEntity->save();

        $fileLocalizedAttributesTransfer->setIdFileLocalizedAttributes(
            $fileLocalizedAttributesEntity->getIdFileLocalizedAttributes()
        );

        return $fileLocalizedAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    public function saveFileDirectory(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fileDirectoryEntity = $this->getFactory()
            ->createFileDirectoryQuery()
            ->filterByIdFileDirectory($fileDirectoryTransfer->getIdFileDirectory())
            ->findOneOrCreate();

        $fileDirectoryEntity = $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileDirectoryTransferToEntity($fileDirectoryTransfer, $fileDirectoryEntity);

        $fileDirectoryEntity->save();
        $fileDirectoryTransfer->setIdFileDirectory($fileDirectoryEntity->getIdFileDirectory());

        return $fileDirectoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer $fileDirectoryLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer
     */
    public function saveFileDirectoryLocalizedAttribute(FileDirectoryLocalizedAttributesTransfer $fileDirectoryLocalizedAttributesTransfer)
    {
        $fileDirectoryLocalizedAttributesEntity = $this->getFactory()
            ->createFileDirectoryLocalizedAttributesQuery()
            ->filterByIdFileDirectoryLocalizedAttributes($fileDirectoryLocalizedAttributesTransfer->getIdFileDirectoryLocalizedAttributes())
            ->findOneOrCreate();

        $fileDirectoryLocalizedAttributesEntity = $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileDirectoryLocalizedAttributesTransferToEntity($fileDirectoryLocalizedAttributesTransfer, $fileDirectoryLocalizedAttributesEntity);

        $fileDirectoryLocalizedAttributesEntity->save();

        $fileDirectoryLocalizedAttributesTransfer->setIdFileDirectoryLocalizedAttributes(
            $fileDirectoryLocalizedAttributesEntity->getIdFileDirectoryLocalizedAttributes()
        );

        return $fileDirectoryLocalizedAttributesTransfer;
    }

    /**
     * @param int $idFileDirectory
     *
     * @return bool
     */
    public function deleteDirectoryFiles(int $idFileDirectory)
    {
        $this->getFactory()
            ->createFileQuery()
            ->filterByFkFileDirectory($idFileDirectory)
            ->delete();

        return true;
    }

    /**
     * @param int $idFileDirectory
     *
     * @return bool
     */
    public function deleteDirectory(int $idFileDirectory)
    {
        $directoryEntity = $this->getFactory()
            ->createFileDirectoryQuery()
            ->filterByIdFileDirectory($idFileDirectory)
            ->findOne();

        if ($directoryEntity === null) {
            return false;
        }

        $directoryEntity->delete();

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer
     */
    public function saveMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        $mimeTypeEntity = $this->getFactory()
            ->createMimeTypeQuery()
            ->filterByIdMimeType($mimeTypeTransfer->getIdMimeType())
            ->findOneOrCreate();

        $mimeTypeEntity = $this->getFactory()
            ->createFileManagerMapper()
            ->mapMimeTypeTransferToEntity($mimeTypeTransfer, $mimeTypeEntity);

        $mimeTypeEntity->save();
        $mimeTypeTransfer->setIdMimeType($mimeTypeEntity->getIdMimeType());

        return $mimeTypeTransfer;
    }

    /**
     * @param int $idMimeType
     *
     * @return void
     */
    public function deleteMimeType(int $idMimeType)
    {
        $this->getFactory()
            ->createMimeTypeQuery()
            ->filterByIdMimeType($idMimeType)
            ->delete();
    }
}
