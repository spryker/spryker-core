<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlIdentifiersRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Mapper\UrlIdentifierMapper;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Mapper\UrlIdentifierMapperInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Reader\UrlIdentifierReader;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Reader\UrlIdentifierReaderInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder\UrlIdentifierResponseBuilder;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder\UrlIdentifierResponseBuilderInterface;

class UrlIdentifiersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Reader\UrlIdentifierReaderInterface
     */
    public function createUrlIdentifierReader(): UrlIdentifierReaderInterface
    {
        return new UrlIdentifierReader(
            $this->getUrlStorageClient(),
            $this->createUrlIdentifierResponseBuilder(),
            $this->createUrlIdentifierMapper(),
            $this->getResourceIdentifierProviderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder\UrlIdentifierResponseBuilderInterface
     */
    public function createUrlIdentifierResponseBuilder(): UrlIdentifierResponseBuilderInterface
    {
        return new UrlIdentifierResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Mapper\UrlIdentifierMapperInterface
     */
    public function createUrlIdentifierMapper(): UrlIdentifierMapperInterface
    {
        return new UrlIdentifierMapper();
    }

    /**
     * @return \Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface
     */
    public function getUrlStorageClient(): UrlIdentifiersRestApiToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(UrlIdentifiersRestApiDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return \Spryker\Glue\UrlIdentifiersRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[]
     */
    public function getResourceIdentifierProviderPlugins(): array
    {
        return $this->getProvidedDependency(UrlIdentifiersRestApiDependencyProvider::PLUGINS_RESOURCE_IDENTIFIER_PROVIDER);
    }
}
