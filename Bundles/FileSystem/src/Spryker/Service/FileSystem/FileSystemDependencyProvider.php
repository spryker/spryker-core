<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemReaderPlugin;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemStreamPlugin;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemWriterPlugin;
use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class FileSystemDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGIN_READER = 'plugin reader';
    const PLUGIN_WRITER = 'plugin writer';
    const PLUGIN_STREAM = 'plugin stream';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
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
        $container[static::PLUGIN_READER] = function (Container $container) {
            return new FileSystemReaderPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addFileSystemWriterPlugin(Container $container)
    {
        $container[static::PLUGIN_WRITER] = function (Container $container) {
            return new FileSystemWriterPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addFileSystemStreamPlugin(Container $container)
    {
        $container[static::PLUGIN_STREAM] = function (Container $container) {
            return new FileSystemStreamPlugin();
        };

        return $container;
    }

}
