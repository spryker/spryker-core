<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToCartsRestApiClientBridge;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientBridge;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Service\QuoteRequestsRestApiToShipmentServiceBridge;

/**
 * @method \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig getConfig()
 */
class QuoteRequestsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';
    public const CLIENT_CARTS_REST_API = 'CLIENT_CARTS_REST_API';
    public const CLIENT_QUOTE_REQUEST = 'CLIENT_QUOTE_REQUEST';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addShipmentService($container);
        $container = $this->addCartsRestApiClient($container);
        $container = $this->addQuoteRequestClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addShipmentService(Container $container): Container
    {
        $container->set(static::SERVICE_SHIPMENT, function (Container $container) {
            return new QuoteRequestsRestApiToShipmentServiceBridge(
                $container->getLocator()->shipment()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCartsRestApiClient(Container $container): Container
    {
        $container->set(static::CLIENT_CARTS_REST_API, function (Container $container) {
            return new QuoteRequestsRestApiToCartsRestApiClientBridge(
                $container->getLocator()->cartsRestApi()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQuoteRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE_REQUEST, function (Container $container) {
            return new QuoteRequestsRestApiToQuoteRequestClientBridge(
                $container->getLocator()->quoteRequest()->client()
            );
        });

        return $container;
    }
}
