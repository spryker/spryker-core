<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemStreamException;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemWriteException;
use Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemReaderPluginInterface;
use Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemStreamPluginInterface;
use Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemWriterPluginInterface;
use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

/**
 * @method \Spryker\Service\FileSystem\FileSystemConfig getConfig()
 */
class FileSystemDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGIN_READER = 'PLUGIN_READER';

    /**
     * @var string
     */
    public const PLUGIN_WRITER = 'PLUGIN_WRITER';

    /**
     * @var string
     */
    public const PLUGIN_STREAM = 'PLUGIN_STREAM';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = parent::provideServiceDependencies($container);

        $container = $this->addFileSystemReaderPlugin($container);
        $container = $this->addFileSystemWriterPlugin($container);
        $container = $this->addFileSystemStreamPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addFileSystemReaderPlugin(Container $container)
    {
        $container->set(static::PLUGIN_READER, function (Container $container) {
            return $this->getFileSystemReaderPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return \Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemReaderPluginInterface
     */
    protected function getFileSystemReaderPlugin(): FileSystemReaderPluginInterface
    {
        throw new FileSystemReadException();
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addFileSystemWriterPlugin(Container $container)
    {
        $container->set(static::PLUGIN_WRITER, function (Container $container) {
            return $this->getFileSystemWriterPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemWriteException
     *
     * @return \Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemWriterPluginInterface
     */
    protected function getFileSystemWriterPlugin(): FileSystemWriterPluginInterface
    {
        throw new FileSystemWriteException();
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addFileSystemStreamPlugin(Container $container)
    {
        $container->set(static::PLUGIN_STREAM, function (Container $container) {
            return $this->getFileSystemStreamPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemStreamException
     *
     * @return \Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemStreamPluginInterface
     */
    protected function getFileSystemStreamPlugin(): FileSystemStreamPluginInterface
    {
        throw new FileSystemStreamException();
    }
}
