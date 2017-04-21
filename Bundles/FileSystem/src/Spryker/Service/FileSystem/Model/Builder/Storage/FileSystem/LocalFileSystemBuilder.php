<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Builder\Storage\FileSystem;

use Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer;
use Generated\Shared\Transfer\FileSystemStorageConfigTransfer;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;
use Spryker\Service\FileSystem\Model\Builder\FileSystemStorageBuilderInterface;
use Spryker\Service\FileSystem\Model\FileSystemStorage;

class LocalFileSystemBuilder implements FileSystemStorageBuilderInterface
{

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \League\Flysystem\Adapter\Local
     */
    protected $adapter;

    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Generated\Shared\Transfer\FileSystemStorageConfigTransfer
     */
    protected $fileSystemConfig;

    /**
     * @var \Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer
     */
    protected $adapterConfig;

    /**
     * @param \Generated\Shared\Transfer\FileSystemStorageConfigTransfer $fileSystemConfig
     * @param \Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer $adapterConfig
     */
    public function __construct(
        FileSystemStorageConfigTransfer $fileSystemConfig,
        FileSystemStorageConfigLocalTransfer $adapterConfig
    ) {
        $this->fileSystemConfig = $fileSystemConfig;
        $this->adapterConfig = $adapterConfig;
    }

    /**
     * Sample config
     * 'root' => '/data/uploads/',
     * 'path' => 'customers/pds/',
     *
     * @return \Spryker\Service\FileSystem\Model\FileSystemStorageInterface
     */
    public function build()
    {
        $this
            ->buildPath()
            ->buildAdapter()
            ->buildFileSystem();

        return new FileSystemStorage($this->fileSystemConfig, $this->filesystem);
    }

    /**
     * @return $this
     */
    protected function buildPath()
    {
        $this->path = sprintf(
            '%s%s%s',
            $this->adapterConfig->getRoot(),
            DIRECTORY_SEPARATOR,
            $this->adapterConfig->getPath()
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function buildAdapter()
    {
        $this->adapter = new LocalAdapter($this->path, LOCK_EX, LocalAdapter::DISALLOW_LINKS);

        return $this;
    }

    /**
     * @return $this
     */
    protected function buildFileSystem()
    {
        $this->filesystem = new Filesystem($this->adapter);

        return $this;
    }

}
