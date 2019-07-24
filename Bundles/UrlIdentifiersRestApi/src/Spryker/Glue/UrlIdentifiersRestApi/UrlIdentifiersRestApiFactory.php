<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlIdentifiersRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\UrlIdentifiersReader;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\UrlIdentifiersReaderInterface;

class UrlIdentifiersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\UrlIdentifiersReaderInterface
     */
    public function createUrlIdentifiersReader(): UrlIdentifiersReaderInterface
    {
        return new UrlIdentifiersReader(
            $this->getUrlStorageClient(),
            $this->getResourceBuilder(),
            $this->getResourceIdentifierProviderPlugins()
        );
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
