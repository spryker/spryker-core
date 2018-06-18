<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Generated\Shared\Transfer\SpyFileInfoEntityTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerPersistenceFactory getFactory()
 */
class FileManagerRepository extends AbstractRepository implements FileManagerRepositoryInterface
{
    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileTransfer|null
     */
    public function getFileByIdFile(int $idFile)
    {
        $query = $this->getFactory()->createFileQuery();
        $fileEntity = $query->findOneByIdFile($idFile);

        if ($fileEntity === null) {
            return $fileEntity;
        }

        return $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileEntityToTransfer($fileEntity, new FileTransfer());
    }

    /**
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileTransfer|null
     */
    public function getFileByIdFileInfo(int $idFileInfo)
    {
        $query = $this->getFactory()
            ->createFileQuery()
            ->useSpyFileInfoQuery()
            ->filterByIdFileInfo($idFileInfo)
            ->endUse();

        $fileEntity = $query->findOne();

        if ($fileEntity === null) {
            return $fileEntity;
        }

        return $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileEntityToTransfer($fileEntity, new FileTransfer());
    }

    /**
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    public function getFileInfo(int $idFileInfo)
    {
        $query = $this->getFactory()
            ->createFileInfoQuery()
            ->filterByIdFileInfo($idFileInfo);

        $fileInfoEntity = $query->findOne();

        if ($fileInfoEntity === null) {
            return $fileInfoEntity;
        }

        return $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileInfoEntityToTransfer($fileInfoEntity, new FileInfoTransfer());
    }

    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\SpyFileInfoEntityTransfer|null
     */
    public function getLatestFileInfoByIdFile(int $idFile)
    {
        $query = $this->getFactory()
            ->createFileInfoQuery()
            ->orderByVersion(Criteria::DESC)
            ->filterByFkFile($idFile);

        $fileInfoEntity = $query->findOne();

        if ($fileInfoEntity === null) {
            return $fileInfoEntity;
        }

        return $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileInfoEntityToEntityTransfer($fileInfoEntity, new SpyFileInfoEntityTransfer());
    }

    /**
     * @param int $idFileDirectory
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer|null
     */
    public function getFileDirectory(int $idFileDirectory)
    {
        $query = $this->getFactory()
            ->createFileDirectoryQuery()
            ->filterByIdFileDirectory($idFileDirectory);

        $fileDirectoryEntity = $query->findOne();

        if ($fileDirectoryEntity === null) {
            return $fileDirectoryEntity;
        }

        return $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileDirectoryEntityToTransfer($fileDirectoryEntity, new FileDirectoryTransfer());
    }

    /**
     * @param int $idFileDirectory
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FileTransfer[]
     */
    public function getDirectoryFiles(int $idFileDirectory)
    {
        $directoryFilesCollection = new ArrayObject();
        $mapper = $this->getFactory()->createFileManagerMapper();

        $query = $this->getFactory()
            ->createFileQuery()
            ->filterByFkFileDirectory($idFileDirectory);

        $files = $query->find();

        foreach ($files as $file) {
            $directoryFilesCollection->append(
                $mapper->mapFileEntityToTransfer($file, new FileTransfer())
            );
        }

        return $directoryFilesCollection;
    }

    /**
     * @param int $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer|null
     */
    public function getMimeType(int $idMimeType)
    {
        $query = $this->getFactory()
            ->createMimeTypeQuery()
            ->filterByIdMimeType($idMimeType);

        $mimeTypeEntity = $query->findOne();

        if ($mimeTypeEntity === null) {
            return $mimeTypeEntity;
        }

        return $this->getFactory()
            ->createFileManagerMapper()
            ->mapMimeTypeEntityToTransfer($mimeTypeEntity, new MimeTypeTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function getMimeTypeByIdMimeTypeAndName(MimeTypeTransfer $mimeTypeTransfer)
    {
        $query = $this->getFactory()
            ->createMimeTypeQuery()
            ->filterByName($mimeTypeTransfer->getName());

        if ($mimeTypeTransfer->getIdMimeType()) {
            $query->filterByIdMimeType($mimeTypeTransfer->getIdMimeType(), Criteria::NOT_EQUAL);
        }

        $mimeTypeEntity = $query->findOne();

        if ($mimeTypeEntity === null) {
            return $mimeTypeEntity;
        }

        return $this->getFactory()
            ->createFileManagerMapper()
            ->mapMimeTypeEntityToTransfer($mimeTypeEntity, new MimeTypeTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    public function getAllowedMimeTypes()
    {
        $mimeTypeCollectionTransfer = new MimeTypeCollectionTransfer();

        $query = $this->getFactory()
            ->createMimeTypeQuery()
            ->filterByIsAllowed(true);

        $mimeTypeCollection = $query->find();
        $mapper = $this->getFactory()->createFileManagerMapper();

        foreach ($mimeTypeCollection as $mimeType) {
            $mimeTypeCollectionTransfer->addMimeType(
                $mapper->mapMimeTypeEntityToTransfer($mimeType, new MimeTypeTransfer())
            );
        }

        return $mimeTypeCollectionTransfer;
    }
}
