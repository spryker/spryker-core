<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui;

use Spryker\Spryk\SprykFacade;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToDevelopmentFacadeBridge;
use Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeBridge;

class SprykGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SPRYK_FACADE = 'SPRYK_FACADE';
    public const DEVELOPMENT_FACADE = 'DEVELOPMENT_FACADE';
    public const PLUGIN_GRAPH = 'PLUGIN_GRAPH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addSprykFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addSprykFacade($container);
        $container = $this->addDevelopmentFacade($container);
        $container = $this->addGraphPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSprykFacade(Container $container): Container
    {
        $container[static::SPRYK_FACADE] = function () {
            return new SprykGuiToSprykFacadeBridge(
                new SprykFacade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDevelopmentFacade(Container $container): Container
    {
        $container[static::DEVELOPMENT_FACADE] = function () use ($container) {
            return new SprykGuiToDevelopmentFacadeBridge(
                $container->getLocator()->development()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGraphPlugin(Container $container): Container
    {
        $container[static::PLUGIN_GRAPH] = function () {
            return new GraphPlugin();
        };

        return $container;
    }
}
