<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder\Storage\Flysystem;

use Generated\Shared\Transfer\FlysystemStorageConfigLocalTransfer;
use Generated\Shared\Transfer\FlysystemStorageConfigTransfer;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;
use Spryker\Service\Flysystem\Model\Builder\FlysystemStorageBuilderInterface;
use Spryker\Service\Flysystem\Model\FlysystemStorage;

class LocalFlysystemBuilder implements FlysystemStorageBuilderInterface
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
     * @var \Generated\Shared\Transfer\FlysystemStorageConfigTransfer
     */
    protected $fileSystemConfig;

    /**
     * @var \Generated\Shared\Transfer\FlysystemStorageConfigLocalTransfer
     */
    protected $adapterConfig;

    /**
     * @param \Generated\Shared\Transfer\FlysystemStorageConfigTransfer $fileSystemConfig
     * @param \Generated\Shared\Transfer\FlysystemStorageConfigLocalTransfer $adapterConfig
     */
    public function __construct(
        FlysystemStorageConfigTransfer $fileSystemConfig,
        FlysystemStorageConfigLocalTransfer $adapterConfig
    ) {
        $this->fileSystemConfig = $fileSystemConfig;
        $this->adapterConfig = $adapterConfig;
    }

    /**
     * Sample config
     * 'root' => '/data/uploads/',
     * 'path' => 'customers/pds/',
     *
     * @return \League\Flysystem\Filesystem
     */
    public function build()
    {
        $this
            ->buildPath()
            ->buildAdapter()
            ->buildFlysystem();

        return $this->filesystem;
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
    protected function buildFlysystem()
    {
        $this->filesystem = new Filesystem($this->adapter);

        return $this;
    }

}
