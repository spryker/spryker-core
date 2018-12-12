<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development;

use Nette\DI\Config\Loader;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * @method \Spryker\Zed\Development\DevelopmentConfig getConfig()
 */
class DevelopmentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_GRAPH = 'graph plugin';
    public const FINDER = 'finder';
    public const FILESYSTEM = 'filesystem';
    public const CONFIG_LOADER = 'config loader';
    public const TWIG_ENVIRONMENT = 'twig environment';
    public const TWIG_LOADER_FILESYSTEM = 'twig loader filesystem';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::PLUGIN_GRAPH] = function () {
            return $this->getGraphPlugin();
        };

        $container[static::FINDER] = function () {
            return $this->createFinder();
        };

        $container[static::FILESYSTEM] = function () {
            return $this->createFilesystem();
        };

        $container[static::CONFIG_LOADER] = function () {
            return $this->createConfigLoader();
        };

        $container[static::TWIG_ENVIRONMENT] = function () {
            return $this->createTwigEnvironment();
        };

        $container[static::TWIG_LOADER_FILESYSTEM] = function () {
            return $this->createTwigLoaderFilesystem();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Graph\GraphInterface
     */
    protected function getGraphPlugin()
    {
        return new GraphPlugin();
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createFinder()
    {
        return Finder::create();
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function createFilesystem()
    {
        return new Filesystem();
    }

    /**
     * @return \Nette\DI\Config\Loader
     */
    protected function createConfigLoader()
    {
        return new Loader();
    }

    /**
     * @return \Twig_Environment
     */
    protected function createTwigEnvironment()
    {
        return new Twig_Environment($this->createTwigLoaderFilesystem());
    }

    /**
     * @return \Twig_Loader_Filesystem
     */
    protected function createTwigLoaderFilesystem()
    {
        return new Twig_Loader_Filesystem();
    }
}
