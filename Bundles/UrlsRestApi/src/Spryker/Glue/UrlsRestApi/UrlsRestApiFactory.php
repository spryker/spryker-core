<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlsRestApi\Processor\Url\Mapper\UrlMapper;
use Spryker\Glue\UrlsRestApi\Processor\Url\Mapper\UrlMapperInterface;
use Spryker\Glue\UrlsRestApi\Processor\Url\Reader\UrlReader;
use Spryker\Glue\UrlsRestApi\Processor\Url\Reader\UrlReaderInterface;
use Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilder;
use Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilderInterface;

class UrlsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\UrlsRestApi\Processor\Url\Reader\UrlReaderInterface
     */
    public function createUrlReader(): UrlReaderInterface
    {
        return new UrlReader(
            $this->getUrlStorageClient(),
            $this->createUrlResponseBuilder(),
            $this->createUrlMapper(),
            $this->getResourceIdentifierProviderPlugins()
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
     * @return \Spryker\Glue\UrlsRestApi\Processor\Url\Mapper\UrlMapperInterface
     */
    public function createUrlMapper(): UrlMapperInterface
    {
        return new UrlMapper();
    }

    /**
     * @return \Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface
     */
    public function getUrlStorageClient(): UrlsRestApiToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(UrlsRestApiDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return \Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[]
     */
    public function getResourceIdentifierProviderPlugins(): array
    {
        return $this->getProvidedDependency(UrlsRestApiDependencyProvider::PLUGINS_RESOURCE_IDENTIFIER_PROVIDER);
    }
}
