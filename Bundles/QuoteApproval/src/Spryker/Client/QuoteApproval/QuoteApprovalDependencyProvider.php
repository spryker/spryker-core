<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientBridge;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientBridge;

/**
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalConfig getConfig()
 */
class QuoteApprovalDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const PLUGINS_QUOTE_APPLICABLE_FOR_APPROVAL_CHECK = 'PLUGINS_QUOTE_APPLICABLE_FOR_APPROVAL_CHECK';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addQuoteApplicableForApprovalCheckPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return new QuoteApprovalToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new QuoteApprovalToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteApplicableForApprovalCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUOTE_APPLICABLE_FOR_APPROVAL_CHECK, function () {
            return $this->getQuoteApplicableForApprovalCheckPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\QuoteApprovalExtension\Dependency\Plugin\QuoteApplicableForApprovalCheckPluginInterface[]
     */
    protected function getQuoteApplicableForApprovalCheckPlugins(): array
    {
        return [];
    }
}
