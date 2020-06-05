<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientBridge;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnSearchClientBridge;

/**
 * @method \Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig getConfig()
 */
class SalesReturnsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SALES_RETURN = 'CLIENT_SALES_RETURN';
    public const CLIENT_SALES_RETURN_SEARCH = 'CLIENT_SALES_RETURN_SEARCH';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addSalesReturnClient($container);
        $container = $this->addSalesReturnSearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSalesReturnClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES_RETURN, function (Container $container) {
            return new SalesReturnsRestApiToSalesReturnClientBridge(
                $container->getLocator()->salesReturn()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSalesReturnSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES_RETURN_SEARCH, function (Container $container) {
            return new SalesReturnsRestApiToSalesReturnSearchClientBridge(
                $container->getLocator()->salesReturnSearch()->client()
            );
        });

        return $container;
    }
}
