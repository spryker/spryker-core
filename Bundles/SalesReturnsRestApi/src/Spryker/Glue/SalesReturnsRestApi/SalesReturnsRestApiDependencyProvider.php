<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToGlossaryStorageClientBridge;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientBridge;
use Spryker\Glue\SalesReturnsRestApi\Dependency\RestApiResource\SalesReturnsRestApiToOrdersRestApiResourceBridge;

/**
 * @method \Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig getConfig()
 */
class SalesReturnsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SALES_RETURN = 'CLIENT_SALES_RETURN';
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    public const RESOURCE_ORDERS_REST_API = 'RESOURCE_ORDERS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        
        $container = $this->addSalesReturnClient($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addOrdersRestApiResource($container);

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
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new SalesReturnsRestApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOrdersRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_ORDERS_REST_API, function (Container $container) {
            return new SalesReturnsRestApiToOrdersRestApiResourceBridge(
                $container->getLocator()->ordersRestApi()->resource()
            );
        });

        return $container;
    }
}
