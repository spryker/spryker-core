<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence\Mapper;

use Generated\Shared\Transfer\FileStorageTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage;
use Propel\Runtime\Collection\ObjectCollection;

interface FileManagerStorageMapperInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function mapFileEntityToTransfer(SpyFile $file, FileTransfer $fileTransfer);

    /**
     * @param \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage $fileStorage
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     *
     * @return \Generated\Shared\Transfer\FileStorageTransfer
     */
    public function mapFileStorageEntityToTransfer(SpyFileStorage $fileStorage, FileStorageTransfer $fileStorageTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     * @param \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage $fileStorage
     *
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage
     */
    public function mapFileStorageTransferToEntity(FileStorageTransfer $fileStorageTransfer, SpyFileStorage $fileStorage);

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage[] $fileStorageEntities
     *
     * @return \Generated\Shared\Transfer\FileStorageTransfer[]
     */
    public function mapFileStorageEntityCollectionToTransferCollection(ObjectCollection $fileStorageEntities): array;
}
