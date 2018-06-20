<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\FileStorageTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\FileManagerStorage\Persistence\Map\SpyFileStorageTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\FileManagerStorage\FileManagerStorageConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStoragePersistenceFactory getFactory()
 */
class FileManagerStorageRepository extends AbstractRepository implements FileManagerStorageRepositoryInterface
{
    /**
     * @param array $fileIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FileTransfer[]
     */
    public function getFilesByIds(array $fileIds)
    {
        $fileEntityTransferCollection = new ArrayObject();
        $mapper = $this->getFactory()->createFileManagerStorageMapper();
        $query = $this->getFactory()
            ->createFileQuery()
            ->filterByIdFile($fileIds, Criteria::IN);

        $fileEntityCollection = $query->find();

        foreach ($fileEntityCollection as $fileEntity) {
            $fileEntityTransferCollection->append(
                $mapper->mapFileEntityToTransfer($fileEntity, new FileTransfer())
            );
        }

        return $fileEntityTransferCollection;
    }

    /**
     * @param array $fileStorageIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\SpyFileStorageEntityTransfer[]
     */
    public function getFileStoragesByIds(array $fileStorageIds)
    {
        $fileStorageTransferCollection = new ArrayObject();
        $mapper = $this->getFactory()->createFileManagerStorageMapper();
        $query = $this->getFactory()
            ->createFileStorageQuery()
            ->filterByFkFile_In($fileStorageIds)
            ->withColumn(
                "CONCAT(" . SpyFileStorageTableMap::COL_FK_FILE . ", '_', " . SpyFileStorageTableMap::COL_LOCALE . ")",
                FileManagerStorageConstants::STORAGE_COMPOSITE_KEY
            );

        $fileStorageEntityCollection = $query->find()->toKeyIndex(FileManagerStorageConstants::STORAGE_COMPOSITE_KEY);

        foreach ($fileStorageEntityCollection as $compositeKey => $fileStorageEntity) {
            $fileStorageTransferCollection[$compositeKey] = $mapper->mapFileStorageEntityToTransfer($fileStorageEntity, new FileStorageTransfer());
        }

        return $fileStorageTransferCollection;
    }
}
