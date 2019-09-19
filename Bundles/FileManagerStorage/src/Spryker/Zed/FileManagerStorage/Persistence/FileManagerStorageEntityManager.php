<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

use Generated\Shared\Transfer\FileStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStoragePersistenceFactory getFactory()
 */
class FileManagerStorageEntityManager extends AbstractEntityManager implements FileManagerStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     *
     * @return void
     */
    public function saveFileStorage(FileStorageTransfer $fileStorageTransfer)
    {
        $fileStorageEntity = $this->getFactory()
            ->createFileStorageQuery()
            ->filterByIdFileStorage($fileStorageTransfer->getIdFileStorage())
            ->findOneOrCreate();

        $fileStorageEntity = $this->getFactory()
            ->createFileManagerStorageMapper()
            ->mapFileStorageTransferToEntity($fileStorageTransfer, $fileStorageEntity);

        $fileStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     *
     * @return bool
     */
    public function deleteFileStorage(FileStorageTransfer $fileStorageTransfer)
    {
        $fileStorageEntity = $this->getFactory()
            ->createFileStorageQuery()
            ->filterByIdFileStorage($fileStorageTransfer->getIdFileStorage())
            ->findOne();

        if ($fileStorageEntity === null) {
            return false;
        }

        $fileStorageEntity->delete();

        return true;
    }
}
