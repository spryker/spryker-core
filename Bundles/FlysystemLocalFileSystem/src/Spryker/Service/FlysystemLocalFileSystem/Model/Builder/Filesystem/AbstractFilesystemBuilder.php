<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemLocalFileSystem\Model\Builder\Filesystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\Filesystem;

abstract class AbstractFilesystemBuilder implements FilesystemBuilderInterface
{

    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected $config;

    /**
     * @var \Spryker\Service\FlysystemLocalFileSystem\Model\Provider\FlysystemPluginProviderInterface
     */
    protected $pluginProvider;

    /**
     * @throws \Spryker\Service\FlysystemLocalFileSystem\Exception\InvalidConfigurationException
     *
     * @return void
     */
    abstract protected function assertAdapterConfig();

    /**
     * @return \Spryker\Service\FlysystemLocalFileSystem\Model\Builder\Adapter\AdapterBuilderInterface
     */
    abstract protected function createAdapterBuilder();

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $config
     */
    public function __construct(
        FlysystemConfigTransfer $config
    ) {
        $this->config = $config;
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function build()
    {
        $this->assertConfig();
        $this->assertAdapterConfig();

        $filesystem = $this->buildFilesystem();
        //$filesystem = $this->pluginProvider->provide($filesystem);

        return $filesystem;
    }

    /**
     * @return void
     */
    protected function assertConfig()
    {
        $this->config->requireName();
        $this->config->requireType();
        $this->config->requireAdapterConfig();
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    protected function buildFilesystem()
    {
        $adapter = $this->createAdapterBuilder()->build();
        $config = $this->config->getFlysystemConfig() ?: [];

        return new Filesystem($adapter, $config);
    }

}
