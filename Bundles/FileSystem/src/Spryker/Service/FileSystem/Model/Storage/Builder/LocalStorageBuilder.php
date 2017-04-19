<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage\Builder;

use Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;
use Spryker\Service\FileSystem\Model\Storage\AbstractStorageBuilder;
use Spryker\Service\FileSystem\Model\Storage\FileSystemStorage;

class LocalStorageBuilder extends AbstractStorageBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer
     */
    protected function buildStorageConfig()
    {
        $configTransfer = new FileSystemStorageConfigLocalTransfer();
        $configTransfer->fromArray($this->config->getData(), true);

        return $configTransfer;
    }

    /**
     * @return void
     */
    protected function validateStorageConfig()
    {
        $storageConfigTransfer = $this->buildStorageConfig();

        $storageConfigTransfer->requirePath();
        $storageConfigTransfer->requireRoot();
    }

    /**
     * Sample config
     * 'root' => '/data/uploads/',
     * 'path' => 'customers/pds/',
     *
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
     */
    protected function buildStorage()
    {
        $path = $this->buildPath();
        $adapter = $this->buildAdapter($path);
        $fileSystem = $this->buildFileSystem($adapter);

        return new FileSystemStorage($this->config, $fileSystem);
    }

    /**
     * @return string
     */
    protected function buildPath()
    {
        $storageConfigTransfer = $this->buildStorageConfig();

        $path = sprintf(
            '%s%s%s',
            $storageConfigTransfer->getRoot(),
            DIRECTORY_SEPARATOR,
            $storageConfigTransfer->getPath()
        );

        return $path;
    }

    /**
     * @param string $path
     *
     * @return \League\Flysystem\Adapter\Local
     */
    protected function buildAdapter($path)
    {
        $adapter = new LocalAdapter($path, LOCK_EX, LocalAdapter::DISALLOW_LINKS);

        return $adapter;
    }

    /**
     * @param \League\Flysystem\Adapter\Local $adapter
     *
     * @return \League\Flysystem\Filesystem
     */
    protected function buildFileSystem(LocalAdapter $adapter)
    {
        $fileSystem = new Filesystem($adapter);

        return $fileSystem;
    }

}
