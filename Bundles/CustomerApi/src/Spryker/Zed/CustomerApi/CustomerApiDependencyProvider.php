<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi;

use Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToApiFacadeBridge;
use Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToCustomerBridge;
use Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiQueryBuilderBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CustomerApi\CustomerApiConfig getConfig()
 */
class CustomerApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const QUERY_CONTAINER_API_QUERY_BUILDER = 'QUERY_CONTAINER_API_QUERY_BUILDER';

    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @var string
     */
    public const FACADE_API = 'FACADE_API';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addApiQueryBuilderQueryContainer($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addApiFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiQueryBuilderQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_API_QUERY_BUILDER, function (Container $container) {
            return new CustomerApiToApiQueryBuilderBridge($container->getLocator()->apiQueryBuilder()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new CustomerApiToCustomerBridge($container->getLocator()->customer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiFacade(Container $container): Container
    {
        $container->set(static::FACADE_API, function (Container $container) {
            return new CustomerApiToApiFacadeBridge($container->getLocator()->api()->facade());
        });

        return $container;
    }
}
