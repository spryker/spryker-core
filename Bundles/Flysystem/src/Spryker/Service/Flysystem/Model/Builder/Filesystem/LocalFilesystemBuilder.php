<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder\Filesystem;

use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;
use Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface;
use Spryker\Service\Flysystem\Model\Provider\FlysystemPluginProviderInterface;

class LocalFilesystemBuilder implements FilesystemBuilderInterface
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
     * @var \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected $fileSystemConfig;

    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigLocalTransfer
     */
    protected $adapterConfig;

    /**
     * @var \Spryker\Service\Flysystem\Model\Provider\FlysystemPluginProviderInterface
     */
    protected $pluginProvider;

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $fileSystemConfig
     * @param \Generated\Shared\Transfer\FlysystemConfigLocalTransfer $adapterConfig
     * @param \Spryker\Service\Flysystem\Model\Provider\FlysystemPluginProviderInterface $pluginProvider
     */
    public function __construct(
        FlysystemConfigTransfer $fileSystemConfig,
        FlysystemConfigLocalTransfer $adapterConfig,
        FlysystemPluginProviderInterface $pluginProvider
    ) {
        $this->fileSystemConfig = $fileSystemConfig;
        $this->adapterConfig = $adapterConfig;
        $this->pluginProvider = $pluginProvider;
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function build()
    {
        $this
            ->buildPath()
            ->buildAdapter()
            ->buildFilesystem()
            ->buildPlugins();

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
    protected function buildFilesystem()
    {
        $this->filesystem = new Filesystem($this->adapter);

        return $this;
    }

    /**
     * @return $this
     */
    protected function buildPlugins()
    {
        $this->filesystem = $this->pluginProvider->provide($this->filesystem);

        return $this;
    }

}
