<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToCustomerFacadeBridge;
use Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToOauthFacadeBridge;

class OauthCustomerConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const FACADE_OAUTH = 'FACADE_OAUTH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addCustomerFacade($container);
        $container = $this->addOauthFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container[static::FACADE_CUSTOMER] = function (Container $container) {
            return new OauthCustomerConnectorToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthFacade(Container $container): Container
    {
        $container[static::FACADE_OAUTH] = function (Container $container) {
            return new OauthCustomerConnectorToOauthFacadeBridge($container->getLocator()->oauth()->facade());
        };

        return $container;
    }
}
