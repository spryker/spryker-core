<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToSearchClientInterface;
use Spryker\Client\MerchantSearch\PaginationConfigBuilder\MerchantSearchPaginationConfigBuilder;
use Spryker\Client\MerchantSearch\PaginationConfigBuilder\PaginationConfigBuilderInterface;
use Spryker\Client\MerchantSearch\Reader\MerchantSearchReader;
use Spryker\Client\MerchantSearch\Reader\MerchantSearchReaderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\MerchantSearch\MerchantSearchConfig getConfig()
 */
class MerchantSearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToSearchClientInterface
     */
    public function getSearchClient(): MerchantSearchToSearchClientInterface
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\MerchantSearch\Reader\MerchantSearchReaderInterface
     */
    public function createMerchantSearchReader(): MerchantSearchReaderInterface
    {
        return new MerchantSearchReader(
            $this->getSearchClient(),
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

    /**
     * @return \Spryker\Client\MerchantSearch\PaginationConfigBuilder\PaginationConfigBuilderInterface
     */
    public function createMerchantSearchPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        return new MerchantSearchPaginationConfigBuilder(
            $this->getConfig()
        );
    }
}
