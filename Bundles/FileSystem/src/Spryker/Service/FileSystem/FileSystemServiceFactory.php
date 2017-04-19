<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use League\Flysystem\Filesystem;
use Spryker\Service\FileSystem\Model\Flysystem\Resource;
use Spryker\Service\FileSystem\Model\Manager\FileSystemManager;
use Spryker\Service\FileSystem\Model\MimeType\MimeTypeManager;
use Spryker\Service\FileSystem\Model\Storage\BuilderCollection;
use Spryker\Service\FileSystem\Model\Storage\FileSystemStorage;
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
        $factory = new BuilderCollection($this->getConfig());

        return $factory->createCollection();
    }

    /**
     * @param array $data
     *
     * @return \Spryker\Service\FileSystem\Model\Flysystem\ResourceInterface
     */
    public function createFlysystemResource(array $data)
    {
        return new Resource($data);
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\MimeType\MimeTypeManagerInterface
     */
    public function createMimeTypeManager()
    {
        return new MimeTypeManager();
    }

}
