<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch\Reader;

use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Client\ServicePointSearch\Dependency\Client\ServicePointSearchToSearchClientInterface;

class ServicePointSearchReader implements ServicePointSearchReaderInterface
{
    /**
     * @var \Spryker\Client\ServicePointSearch\Dependency\Client\ServicePointSearchToSearchClientInterface
     */
    protected ServicePointSearchToSearchClientInterface $searchClient;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected QueryInterface $servicePointSearchQueryPlugin;

    /**
     * @var list<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected array $servicePointSearchQueryExpanderPlugins;

    /**
     * @var list<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    protected array $servicePointSearchResultFormatterPlugins;

    /**
     * @param \Spryker\Client\ServicePointSearch\Dependency\Client\ServicePointSearchToSearchClientInterface $searchClient
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $servicePointSearchQueryPlugin
     * @param list<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface> $servicePointSearchQueryExpanderPlugins
     * @param list<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $servicePointSearchResultFormatterPlugins
     */
    public function __construct(
        ServicePointSearchToSearchClientInterface $searchClient,
        QueryInterface $servicePointSearchQueryPlugin,
        array $servicePointSearchQueryExpanderPlugins,
        array $servicePointSearchResultFormatterPlugins
    ) {
        $this->searchClient = $searchClient;
        $this->servicePointSearchQueryPlugin = $servicePointSearchQueryPlugin;
        $this->servicePointSearchQueryExpanderPlugins = $servicePointSearchQueryExpanderPlugins;
        $this->servicePointSearchResultFormatterPlugins = $servicePointSearchResultFormatterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer>
     */
    public function searchServicePoints(ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer): array
    {
        $searchQuery = $this->servicePointSearchQueryPlugin;

        $searchString = $servicePointSearchRequestTransfer->getSearchString();
        $requestParameters = $servicePointSearchRequestTransfer->getRequestParameters();

        if ($searchString && $searchQuery instanceof SearchStringSetterInterface) {
            $searchQuery->setSearchString($searchString);
        }

        $searchQuery = $this->searchClient->expandQuery(
            $searchQuery,
            $this->servicePointSearchQueryExpanderPlugins,
            $requestParameters,
        );

        return $this->searchClient->search(
            $searchQuery,
            $this->servicePointSearchResultFormatterPlugins,
            $requestParameters,
        );
    }
}
