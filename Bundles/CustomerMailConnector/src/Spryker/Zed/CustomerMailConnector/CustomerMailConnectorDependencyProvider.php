<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector;

use Spryker\Zed\CustomerMailConnector\Dependency\Facade\CustomerMailConnectorToGlossaryBridge;
use Spryker\Zed\CustomerMailConnector\Dependency\Facade\CustomerMailConnectorToMailBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerMailConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_MAIL = 'mail facade';
    const FACADE_GLOSSARY = 'glossary facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_MAIL] = function (Container $container) {
            return new CustomerMailConnectorToMailBridge($container->getLocator()->mail()->facade());
        };
        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new CustomerMailConnectorToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

}
