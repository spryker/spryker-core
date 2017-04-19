<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Generated\Shared\Transfer\FileSystemResourceTransfer;
use League\Flysystem\Filesystem;
use Spryker\Service\FileSystem\Model\Manager\FileSystemManager;
use Spryker\Service\FileSystem\Model\MimeType\MimeTypeManager;
use Spryker\Service\FileSystem\Model\Storage\FileSystemStorage;
use Spryker\Service\FileSystem\Model\Storage\Provider\StorageConfigProvider;
use Spryker\Service\FileSystem\Model\Storage\FileSystemStorageProvider;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\FileSystem\StorageConfig getConfig()
 */
class FileSystemServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \Spryker\Service\FileSystem\Model\Manager\FileSystemManagerInterface
     */
    public function createFileSystemManager()
    {
        return new FileSystemManager(
            $this->createStorageBuilderCollection()
        );
    }

    /**
     * @param array $config
     * @param \League\Flysystem\Filesystem $fileSystem
     *
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
     */
    public function createFileSystemStorage(array $config, Filesystem $fileSystem)
    {
        return new FileSystemStorage($config, $fileSystem);
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Storage\BuilderInterface[]
     */
    protected function createStorageBuilderCollection()
    {
        $provider = new FileSystemStorageProvider(
            $this->createStorageConfigProvider()
        );

        return $provider->createCollection();
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceTransfer
     */
    public function createFileSystemResource(array $data)
    {
        return (new FileSystemResourceTransfer())
            ->fromArray($data, true);
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\MimeType\MimeTypeManagerInterface
     */
    public function createMimeTypeManager()
    {
        return new MimeTypeManager();
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Storage\Provider\StorageConfigProviderInterface
     */
    protected function createStorageConfigProvider()
    {
        return new StorageConfigProvider(
            $this->getConfig()->getStorageConfig()
        );
    }

}
