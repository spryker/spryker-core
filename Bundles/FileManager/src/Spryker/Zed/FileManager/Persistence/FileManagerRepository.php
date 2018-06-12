<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\SpyFileInfoEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerPersistenceFactory getFactory()
 */
class FileManagerRepository extends AbstractRepository implements FileManagerRepositoryInterface
{
    /**
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\SpyFileInfoEntityTransfer|null
     */
    public function getFileInfoById(int $idFileInfo): ?SpyFileInfoEntityTransfer
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $fileInfoEntity = $query->findOneByIdFileInfo($idFileInfo);

        if ($fileInfoEntity === null) {
            return $fileInfoEntity;
        }

        return $this->getFactory()
            ->createFileManagerMapper()
            ->mapFileInfoEntityToTransfer($fileInfoEntity);
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
            ->mapFileInfoEntityToTransfer($fileInfoEntity);
    }

    /**
     * @param int $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeTransfer|null
     */
    public function findMimeType(int $idMimeType)
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
            ->mapMimeTypeEntityToTransfer($mimeTypeEntity);
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
                $mapper->mapMimeTypeEntityToTransfer($mimeType)
            );
        }

        return $mimeTypeCollectionTransfer;
    }
}
