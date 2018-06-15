<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

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
            ->mapFileInfoEntityToTransfer($fileInfoEntity, new SpyFileInfoEntityTransfer());
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
