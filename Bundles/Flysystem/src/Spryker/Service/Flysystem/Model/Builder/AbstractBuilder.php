<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Spryker\Service\Flysystem\Model\Provider\FlysystemPluginProviderInterface;

abstract class AbstractBuilder implements FilesystemBuilderInterface
{

    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected $config;

    /**
     * @var \Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface
     */
    protected $builder;

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
     * @return \Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface
     */
    abstract protected function createFileSystemBuilder();

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

        $filesystemBuilder = $this->createFileSystemBuilder();

        return $filesystemBuilder->build();
    }

    /**
     * @return void
     */
    protected function assertConfig()
    {
        $this->config->requireName();
        $this->config->requireType();
        $this->config->requireData();
    }

}
