<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder\Filesystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\Filesystem;
use Spryker\Service\Flysystem\Model\Provider\FlysystemPluginProviderInterface;

abstract class AbstractFilesystemBuilder implements FilesystemBuilderInterface
{

    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected $config;

    /**
     * @var \Spryker\Service\Flysystem\Model\Provider\FlysystemPluginProviderInterface
     */
    protected $pluginProvider;

    /**
     * @throws \Spryker\Service\Flysystem\Exception\InvalidConfigurationException
     *
     * @return void
     */
    abstract protected function assertAdapterConfig();

    /**
     * @return \Spryker\Service\Flysystem\Model\Builder\Adapter\AdapterBuilderInterface
     */
    abstract protected function createAdapterBuilder();

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $config
     * @param \Spryker\Service\Flysystem\Model\Provider\FlysystemPluginProviderInterface $pluginProvider
     */
    public function __construct(
        FlysystemConfigTransfer $config,
        FlysystemPluginProviderInterface $pluginProvider
    ) {
        $this->config = $config;
        $this->pluginProvider = $pluginProvider;
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function build()
    {
        $this->assertConfig();
        $this->assertAdapterConfig();

        $filesystem = $this->buildFilesystem();
        $filesystem = $this->pluginProvider->provide($filesystem);

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
