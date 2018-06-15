<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

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
    public function saveLocalizedFileAttribute(FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer)
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

        $mimeTypeTransfer->setIdMimeType($mimeTypeEntity->getIdMimeType());
        $mimeTypeEntity->save();

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
