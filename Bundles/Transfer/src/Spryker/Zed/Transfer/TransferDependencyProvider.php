<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class TransferDependencyProvider extends AbstractBundleDependencyProvider
{

    const SYMFONY_FILE_SYSTEM = 'symfony_file_system';
    const SYMFONY_FINDER = 'symfony_finder';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[static::SYMFONY_FILE_SYSTEM] = function () {
            return new Filesystem();
        };

        $container[static::SYMFONY_FINDER] = function () {
            return new Finder();
        };

        return $container;
    }

}
