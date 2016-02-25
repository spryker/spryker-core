<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToGlossaryBridge;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMailBridge;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToSalesAggregatorBridge;

class PayolutionDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_MAIL = 'mail facade';
    const FACADE_GLOSSARY = 'glossary facade';
    const FACADE_SALES_AGGREGATOR = 'sales aggregor facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {

        $container[self::FACADE_MAIL] = function (Container $container) {
            return new PayolutionToMailBridge($container->getLocator()->mail()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new PayolutionToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::FACADE_SALES_AGGREGATOR] = function (Container $container) {
            return new PayolutionToSalesAggregatorBridge($container->getLocator()->salesAggregator()->facade());
        };

        return $container;
    }

}
