<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class MaintenanceDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGIN_GRAPH = 'graph plugin';

    /**
     * @var \Spryker\Zed\Kernel\Container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGIN_GRAPH] = function (Container $container) {
            return $this->getGraphPlugin();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Graph\GraphInterface
     */
    protected function getGraphPlugin()
    {
        return new GraphPlugin('Dependency Tree');
    }

}
