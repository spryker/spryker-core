<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @method \Spryker\Zed\Setup\SetupConfig getConfig()
 */
class SetupDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SYMFONY_FILE_SYSTEM = 'symfony_file_system';

    /**
     * @var string
     */
    public const SYMFONY_FINDER = 'symfony_finder';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container->set(static::SYMFONY_FILE_SYSTEM, function () {
            return new Filesystem();
        });

        $container->set(static::SYMFONY_FINDER, function () {
            return new Finder();
        });

        return $container;
    }
}
