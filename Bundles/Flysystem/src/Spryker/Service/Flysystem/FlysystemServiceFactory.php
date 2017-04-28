<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Spryker\Service\Flysystem\Exception\BuilderNotFoundException;
use Spryker\Service\Flysystem\Model\Provider\FilesystemProvider;
use Spryker\Service\Flysystem\Model\Reader;
use Spryker\Service\Flysystem\Model\Stream;
use Spryker\Service\Flysystem\Model\Writer;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\Flysystem\FlysystemConfig getConfig()
 */
class FlysystemServiceFactory extends AbstractServiceFactory
{

    const SPRYKER_ADAPTER_CLASS = 'sprykerAdapterClass';

    /**
     * @return \Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface
     */
    public function createFilesystemProvider()
    {
        return new FilesystemProvider(
            $this->buildFilesystemCollection()
        );
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\ReaderInterface
     */
    public function createReader()
    {
        return new Reader(
            $this->createFilesystemProvider()
        );
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\WriterInterface
     */
    public function createWriter()
    {
        return new Writer(
            $this->createFilesystemProvider()
        );
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\StreamInterface
     */
    public function createStream()
    {
        return new Stream(
            $this->createFilesystemProvider()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigTransfer[]
     */
    protected function createConfigCollection()
    {
        $configCollection = [];
        foreach ($this->getConfig()->getFilesystemConfig() as $name => $configData) {
            $configTransfer = $this->createConfig($name, $configData);
            $this->assertConfig($configTransfer);

            $configCollection[$name] = $configTransfer;
        }

        return $configCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     *
     * @return void
     */
    protected function assertConfig(FlysystemConfigTransfer $configTransfer)
    {
        $configTransfer->requireName();
        $configTransfer->requireType();
        $configTransfer->requireAdapterConfig();
    }

    /**
     * @param string $name
     * @param array $configData
     *
     * @return \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected function createConfig($name, array $configData)
    {
        $type = $configData[static::SPRYKER_ADAPTER_CLASS];
        unset($configData[static::SPRYKER_ADAPTER_CLASS]);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName($name);
        $configTransfer->setType($type);
        $configTransfer->setAdapterConfig($configData);

        $configTransfer->setFlysystemConfig(
            $this->getConfig()->getFlysystemConfig()
        );

        return $configTransfer;
    }

    /**
     * @return \League\Flysystem\Filesystem[]
     */
    protected function buildFilesystemCollection()
    {
        $configCollection = $this->createConfigCollection();

        $filesystemCollection = [];
        foreach ($configCollection as $name => $configTransfer) {
            $filesystemBuilderPlugin = $this->getFilesystemBuilderPluginByType($configTransfer);

            $filesystemCollection[$name] = $filesystemBuilderPlugin->build(
                $configTransfer,
                $this->getFlysystemPluginCollection()
            );
        }

        return $filesystemCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     *
     * @throws \Spryker\Service\Flysystem\Exception\BuilderNotFoundException
     *
     * @return \Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface
     */
    protected function getFilesystemBuilderPluginByType(FlysystemConfigTransfer $configTransfer)
    {
        $pluginBuilderCollection = $this->getFilesystemBuilderPluginCollection();
        foreach ($pluginBuilderCollection as $plugin) {
            if ($plugin->acceptType($configTransfer->getType())) {
                return $plugin;
            }
        }

        throw new BuilderNotFoundException(sprintf(
            'FlysystemFileSystemBuilderPlugin "%s" was not found',
            $configTransfer->getName()
        ));
    }

    /**
     * @return \League\Flysystem\PluginInterface[]
     */
    protected function getFlysystemPluginCollection()
    {
        return $this->getProvidedDependency(FlysystemDependencyProvider::PLUGIN_COLLECTION_FLYSYSTEM);
    }

    /**
     * @return \Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface[]
     */
    protected function getFilesystemBuilderPluginCollection()
    {
        return $this->getProvidedDependency(FlysystemDependencyProvider::PLUGIN_COLLECTION_FILESYSTEM_BUILDER);
    }

}
