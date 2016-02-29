<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
        $container[self::PLUGIN_GRAPH] = function () {
            return $this->getGraphPlugin();
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

}
