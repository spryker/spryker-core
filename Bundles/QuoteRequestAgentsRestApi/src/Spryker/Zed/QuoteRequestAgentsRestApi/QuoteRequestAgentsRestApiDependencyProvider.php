<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgentsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToCartsRestApiFacadeBridge;
use Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeBridge;

/**
 * @method \Spryker\Zed\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiConfig getConfig()
 */
class QuoteRequestAgentsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CARTS_REST_API = 'FACADE_CARTS_REST_API';

    /**
     * @var string
     */
    public const FACADE_QUOTE_REQUEST_AGENT = 'FACADE_QUOTE_REQUEST_AGENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCartsRestApiFacade($container);
        $container = $this->addQuoteRequestAgentFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteRequestAgentFacade(Container $container): Container
    {
        $container->set(static::FACADE_QUOTE_REQUEST_AGENT, function (Container $container) {
            return new QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeBridge(
                $container->getLocator()->quoteRequestAgent()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartsRestApiFacade(Container $container): Container
    {
        $container->set(static::FACADE_CARTS_REST_API, function (Container $container) {
            return new QuoteRequestAgentsRestApiToCartsRestApiFacadeBridge(
                $container->getLocator()->cartsRestApi()->facade(),
            );
        });

        return $container;
    }
}
