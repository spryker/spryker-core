<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientBridge;

/**
 * @method \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig getConfig()
 */
class QuoteRequestsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_REST_QUOTE_REQUEST_ATTRIBUTES_EXPANDER = 'PLUGINS_REST_QUOTE_REQUEST_ATTRIBUTES_EXPANDER';

    /**
     * @var string
     */
    public const CLIENT_QUOTE_REQUEST = 'CLIENT_QUOTE_REQUEST';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addRestQuoteRequestAttributesExpanderPlugins($container);
        $container = $this->addQuoteRequestClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestQuoteRequestAttributesExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_QUOTE_REQUEST_ATTRIBUTES_EXPANDER, function () {
            return $this->getRestQuoteRequestAttributesExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestAttributesExpanderPluginInterface>
     */
    protected function getRestQuoteRequestAttributesExpanderPlugins(): array
    {
        return [];
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
