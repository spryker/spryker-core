<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderDemo;

use Spryker\Yves\FactFinderDemo\Dependency\Clients\FactFinderDemoToFactFinderClientBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class FactFinderDemoDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACT_FINDER_CLIENT = 'FACT_FINDER_CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->provideClients($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideClients(Container $container)
    {
        $container[self::FACT_FINDER_CLIENT] = function () use ($container) {
            $factFinderClient = $container->getLocator()
                ->factFinder()
                ->client();

            return new FactFinderDemoToFactFinderClientBridge($factFinderClient);
        };

        return $container;
    }

}
