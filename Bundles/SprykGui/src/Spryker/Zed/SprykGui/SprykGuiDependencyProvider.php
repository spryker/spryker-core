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
use Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeBridge;

class SprykGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SPRYK_FACADE = 'spryk facade';
    public const PLUGIN_GRAPH = 'graph plugin';

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
            $sprykGuiToSprykFacadeBridge = new SprykGuiToSprykFacadeBridge(
                new SprykFacade()
            );

            return $sprykGuiToSprykFacadeBridge;
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
