<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToStoreClientInterface;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface;
use Spryker\Client\MerchantSearch\MerchantReader\MerchantReader;
use Spryker\Client\MerchantSearch\MerchantReader\MerchantReaderInterface;
use Spryker\Client\MerchantSearch\MerchantReader\MerchantSearchReader;
use Spryker\Client\MerchantSearch\Zed\MerchantSearchStub;
use Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class MerchantSearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface
     */
    public function createMerchantSearchStub(): MerchantSearchStubInterface
    {
        return new MerchantSearchStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantSearch\MerchantReader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->createMerchantSearchStub(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface
     */
    public function getZedRequestClient(): MerchantSearchToZedRequestClientInterface
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToStoreClientInterface
     */
    public function getStoreClient(): MerchantSearchToStoreClientInterface
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToSearchClientInterface
     */
    public function getSerchClient()
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\MerchantSearch\MerchantReader\MerchantSearchReader
     */
    public function createMerchantSearchReader()
    {
        return new MerchantSearchReader(
            $this->getSerchClient(),
            $this->getMerchantSearchQueryPlugin(),
            $this->getMerchantSearchQueryExpanderPlugins(),
            $this->getMerchantSearchResultFormatterPlugins()
        );
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getMerchantSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::PLUGIN_MERCHANT_SEARCH_QUERY);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getMerchantSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::PLUGINS_MERCHANT_SEARCH_QUERY_EXPANDER);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getMerchantSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::PLUGINS_MERCHANT_SEARCH_RESULT_FORMATTER);
    }
}
