<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlsRestApi\Processor\Url\Resolver\UrlResolver;
use Spryker\Glue\UrlsRestApi\Processor\Url\Resolver\UrlResolverInterface;
use Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilder;
use Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilderInterface;

class UrlsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\UrlsRestApi\Processor\Url\Resolver\UrlResolverInterface
     */
    public function createUrlResolver(): UrlResolverInterface
    {
        return new UrlResolver(
            $this->getUrlStorageClient(),
            $this->createUrlResponseBuilder(),
            $this->getRestUrlResolverAttributesTransferProviderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilderInterface
     */
    public function createUrlResponseBuilder(): UrlResponseBuilderInterface
    {
        return new UrlResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface
     */
    public function getUrlStorageClient(): UrlsRestApiToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(UrlsRestApiDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return \Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\RestUrlResolverAttributesTransferProviderPluginInterface[]
     */
    public function getRestUrlResolverAttributesTransferProviderPlugins(): array
    {
        return $this->getProvidedDependency(UrlsRestApiDependencyProvider::PLUGINS_REST_URL_RESOLVER_ATTRIBUTES_TRANSFER_PROVIDER);
    }
}
