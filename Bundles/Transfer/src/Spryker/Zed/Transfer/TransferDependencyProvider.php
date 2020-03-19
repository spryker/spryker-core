<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Transfer\Dependency\Service\TransferToUtilGlobServiceBridge;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @method \Spryker\Zed\Transfer\TransferConfig getConfig()
 */
class TransferDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_GLOB = 'util glob service';
    public const SYMFONY_FILE_SYSTEM = 'symfony_file_system';
    public const SYMFONY_FINDER = 'symfony_finder';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::SYMFONY_FILE_SYSTEM] = function () {
            return new Filesystem();
        };

        $container[static::SYMFONY_FINDER] = function () {
            return new Finder();
        };

        $container = $this->addUtilGlobService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilGlobService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_GLOB, function (Container $container) {
            return new TransferToUtilGlobServiceBridge(
                $container->getLocator()->utilGlob()->service()
            );
        });

        return $container;
    }
}
