<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class CacheDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SYMFONY_FILE_SYSTEM = 'symfony_file_system';
    public const SYMFONY_FINDER = 'symfony_finder';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addFileSystem($container);
        $container = $this->addFinder($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileSystem(Container $container)
    {
        $container[static::SYMFONY_FILE_SYSTEM] = function () {
            return new Filesystem();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFinder(Container $container)
    {
        $container[static::SYMFONY_FINDER] = function () {
            return new Finder();
        };

        return $container;
    }
}
