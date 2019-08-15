<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgent;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToQuoteRequestFacadeBridge;

/**
 * @method \Spryker\Zed\QuoteRequestAgent\QuoteRequestAgentConfig getConfig()
 */
class QuoteRequestAgentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_QUOTE_REQUEST = 'FACADE_QUOTE_REQUEST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addQuoteRequestFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteRequestFacade(Container $container): Container
    {
        $container[static::FACADE_QUOTE_REQUEST] = function (Container $container) {
            return new QuoteRequestAgentToQuoteRequestFacadeBridge($container->getLocator()->quoteRequest()->facade());
        };

        return $container;
    }
}
