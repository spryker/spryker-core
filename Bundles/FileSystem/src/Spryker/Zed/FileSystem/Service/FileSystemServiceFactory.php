<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service;

use League\Flysystem\Filesystem;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Zed\FileSystem\Service\Flysystem\Resource;
use Spryker\Zed\FileSystem\Service\Manager\FileSystemManager;
use Spryker\Zed\FileSystem\Service\MimeType\MimeTypeManager;
use Spryker\Zed\FileSystem\Service\Storage\BuilderCollectionFactory;
use Spryker\Zed\FileSystem\Service\Storage\FileSystemStorage;

/**
 * @method \Spryker\Zed\FileSystem\FileSystemConfig getConfig()
 */
class FileSystemServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \Spryker\Zed\FileSystem\Service\Manager\FileSystemManagerInterface
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
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface
     */
    public function createFileSystemStorage(array $config, Filesystem $fileSystem)
    {
        return new FileSystemStorage($config, $fileSystem);
    }

    /**
     * @return \Spryker\Zed\FileSystem\Service\Storage\BuilderInterface[]
     */
    protected function createStorageBuilderCollection()
    {
        $factory = new BuilderCollectionFactory($this->getConfig());

        return $factory->createCollection();
    }

    /**
     * @param array $data
     *
     * @return \Spryker\Zed\FileSystem\Service\Flysystem\ResourceInterface
     */
    public function createFlysystemResource(array $data)
    {
        return new Resource($data);
    }

    /**
     * @return \Spryker\Zed\FileSystem\Service\MimeType\MimeTypeManagerInterface
     */
    public function createMimeTypeManager()
    {
        return new MimeTypeManager();
    }

}
