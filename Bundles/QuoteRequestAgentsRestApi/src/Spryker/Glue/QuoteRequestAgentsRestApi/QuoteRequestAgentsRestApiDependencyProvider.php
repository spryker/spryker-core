<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToCompanyUserStorageClientBridge;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientBridge;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceBridge;

/**
 * @method \Spryker\Glue\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiConfig getConfig()
 */
class QuoteRequestAgentsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const RESOURCE_QUOTE_REQUESTS_REST_API = 'RESOURCE_QUOTE_REQUESTS_REST_API';

    /**
     * @var string
     */
    public const CLIENT_QUOTE_REQUEST_AGENT = 'CLIENT_QUOTE_REQUEST_AGENT';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_USER_STORAGE = 'CLIENT_COMPANY_USER_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQuoteRequestsRestApiResource($container);
        $container = $this->addQuoteRequestAgentClient($container);
        $container = $this->addCompanyUserStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQuoteRequestsRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_QUOTE_REQUESTS_REST_API, function (Container $container) {
            return new QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceBridge(
                $container->getLocator()->quoteRequestsRestApi()->resource()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQuoteRequestAgentClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE_REQUEST_AGENT, function (Container $container) {
            return new QuoteRequestAgentsRestApiToQuoteRequestAgentClientBridge($container->getLocator()->quoteRequestAgent()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCompanyUserStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_USER_STORAGE, function (Container $container) {
            return new QuoteRequestAgentsRestApiToCompanyUserStorageClientBridge($container->getLocator()->companyUserStorage()->client());
        });

        return $container;
    }
}
