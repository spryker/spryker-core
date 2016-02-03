<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Touch;

use Spryker\Zed\Propel\Communication\Plugin\Connection;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class TouchDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGIN_PROPEL_CONNECTION] = function () {
            return (new Connection())->get();
        };

        return $container;
    }

}
