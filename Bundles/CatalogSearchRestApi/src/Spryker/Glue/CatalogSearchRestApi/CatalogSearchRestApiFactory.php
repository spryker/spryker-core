<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi;

use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCatalogClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToPriceClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Catalog\CatalogSearchReader;
use Spryker\Glue\CatalogSearchRestApi\Processor\Catalog\CatalogSearchReaderInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapper;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapper;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Translation\CatalogSearchTranslationExpander;
use Spryker\Glue\CatalogSearchRestApi\Processor\Translation\CatalogSearchTranslationExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;

class CatalogSearchRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface
     */
    public function createCatalogSearchResourceMapper(): CatalogSearchResourceMapperInterface
    {
        return new CatalogSearchResourceMapper(
            $this->getCurrencyClient()
        );
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface
     */
    public function createCatalogSearchSuggestionsResourceMapper(): CatalogSearchSuggestionsResourceMapperInterface
    {
        return new CatalogSearchSuggestionsResourceMapper(
            $this->getCurrencyClient()
        );
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Processor\Catalog\CatalogSearchReaderInterface
     */
    public function createCatalogSearchReader(): CatalogSearchReaderInterface
    {
        return new CatalogSearchReader(
            $this->getCatalogClient(),
            $this->getPriceClient(),
            $this->getResourceBuilder(),
            $this->createCatalogSearchResourceMapper(),
            $this->createCatalogSearchSuggestionsResourceMapper(),
            $this->getStore(),
            $this->createCatalogSearchTranslationExpander()
        );
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Processor\Translation\CatalogSearchTranslationExpanderInterface
     */
    public function createCatalogSearchTranslationExpander(): CatalogSearchTranslationExpanderInterface
    {
        return new CatalogSearchTranslationExpander($this->getGlossaryStorageClient());
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCatalogClientInterface
     */
    public function getCatalogClient(): CatalogSearchRestApiToCatalogClientInterface
    {
        return $this->getProvidedDependency(CatalogSearchRestApiDependencyProvider::CLIENT_CATALOG);
    }

    /**
     * @deprecated Will be removed in the next major.
     *
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(CatalogSearchRestApiDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToPriceClientInterface
     */
    protected function getPriceClient(): CatalogSearchRestApiToPriceClientInterface
    {
        return $this->getProvidedDependency(CatalogSearchRestApiDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CatalogSearchRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CatalogSearchRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface
     */
    protected function getCurrencyClient(): CatalogSearchRestApiToCurrencyClientInterface
    {
        return $this->getProvidedDependency(CatalogSearchRestApiDependencyProvider::CLIENT_CURRENCY);
    }
}
