<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\MerchantReader;

use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToSearchClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use function Functional\first;

class MerchantSearchReader implements MerchantSearchReaderInterface
{
    /**
     * @var \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToSearchClientInterface
     */
    protected $searchClient;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected $merchantSearchQueryPlugin;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected $merchantSearchQueryExpanderPlugins;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected $merchantSearchResultFormatterPlugins;

    /**
     * @param \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToSearchClientInterface $searchClient
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $merchantSearchQueryPlugin
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[] $merchantSearchQueryExpanderPlugins
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $merchantSearchResultFormatterPlugins
     */
    public function __construct(
        MerchantSearchToSearchClientInterface $searchClient,
        QueryInterface $merchantSearchQueryPlugin,
        array $merchantSearchQueryExpanderPlugins,
        array $merchantSearchResultFormatterPlugins
    ) {
        $this->searchClient = $searchClient;
        $this->merchantSearchQueryPlugin = $merchantSearchQueryPlugin;
        $this->merchantSearchQueryExpanderPlugins = $merchantSearchQueryExpanderPlugins;
        $this->merchantSearchResultFormatterPlugins = $merchantSearchResultFormatterPlugins;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    public function merchantSearch()
    {
        $searchQuery = $this->searchClient->expandQuery(
            $this->merchantSearchQueryPlugin,
            $this->merchantSearchQueryExpanderPlugins,
        );
        $result = $this->searchClient->search(
            $searchQuery,
            $this->merchantSearchResultFormatterPlugins,
        );

        return first($result);
    }
}
