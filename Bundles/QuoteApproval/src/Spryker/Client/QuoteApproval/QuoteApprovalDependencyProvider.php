<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToCompanyUserClientBridge;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientBridge;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientBridge;

class QuoteApprovalDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const PLUGINS_QUOTE_APPROVAL_CREATE_PRE_CHECK = 'PLUGINS_QUOTE_APPROVAL_CREATE_PRE_CHECK';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addQuoteApprovalCreatePreCheckPlugins($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addQuoteClient($container);

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
    protected function addCompanyUserClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_USER] = function (Container $container) {
            return new QuoteApprovalToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
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
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new QuoteApprovalToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteApprovalCreatePreCheckPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_APPROVAL_CREATE_PRE_CHECK] = function (Container $container) {
            return $this->getQuoteApprovalCreatePreCheckPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\QuoteApprovalExtension\Dependency\Plugin\QuoteApprovalCreatePreCheckPluginInterface[]
     */
    protected function getQuoteApprovalCreatePreCheckPlugins(): array
    {
        return [];
    }
}
