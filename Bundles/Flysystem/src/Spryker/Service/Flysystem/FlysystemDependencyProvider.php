<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class FlysystemDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_COLLECTION_FLYSYSTEM = 'flysystem plugin collection';
    public const PLUGIN_COLLECTION_FILESYSTEM_BUILDER = 'filesystem builder plugin collection';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = $this->addFlysystemPluginCollection($container);
        $container = $this->addFilesystemBuilderPluginCollection($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addFlysystemPluginCollection($container)
    {
        $container[self::PLUGIN_COLLECTION_FLYSYSTEM] = function (Container $container) {
            return [];
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addFilesystemBuilderPluginCollection($container)
    {
        $container[self::PLUGIN_COLLECTION_FILESYSTEM_BUILDER] = function (Container $container) {
            return [];
        };

        return $container;
    }
}
