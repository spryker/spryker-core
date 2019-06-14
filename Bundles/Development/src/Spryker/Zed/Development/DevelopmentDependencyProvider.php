<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development;

use Nette\DI\Config\Loader;
use Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeBridge;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @method \Spryker\Zed\Development\DevelopmentConfig getConfig()
 */
class DevelopmentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MODULE_FINDER = 'module finder facade';
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

        $container = $this->addModuleFinderFacade($container);

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
     * @return \Twig\Environment
     */
    protected function createTwigEnvironment()
    {
        return new Environment($this->createTwigLoaderFilesystem());
    }

    /**
     * @return \Twig\Loader\FilesystemLoader
     */
    protected function createTwigLoaderFilesystem()
    {
        return new FilesystemLoader();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addModuleFinderFacade(Container $container): Container
    {
        $container->set(static::FACADE_MODULE_FINDER, function (Container $container) {
            $developmentToModuleFinderFacadeBridge = new DevelopmentToModuleFinderFacadeBridge(
                $container->getLocator()->moduleFinder()->facade()
            );

            return $developmentToModuleFinderFacadeBridge;
        });

        return $container;
    }
}
