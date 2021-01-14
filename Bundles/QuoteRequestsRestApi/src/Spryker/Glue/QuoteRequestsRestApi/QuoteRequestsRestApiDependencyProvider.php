<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\QuoteRequestsRestApiToShipmentServiceBridge;

/**
 * @method \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig getConfig()
 */
class QuoteRequestsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';
    public const PLUGINS_REST_QUOTE_REQUEST_ITEM_EXPANDER = 'PLUGINS_REST_QUOTE_REQUEST_ITEM_EXPANDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addShipmentService($container);
        $container = $this->addRestQuoteRequestsItemExpanderPlugins($container);

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
    protected function addRestQuoteRequestsItemExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_QUOTE_REQUEST_ITEM_EXPANDER, function () {
            return $this->getRestQuoteRequestsItemExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestsItemExpanderPluginInterface[]
     */
    protected function getRestQuoteRequestsItemExpanderPlugins(): array
    {
        return [];
    }
}
