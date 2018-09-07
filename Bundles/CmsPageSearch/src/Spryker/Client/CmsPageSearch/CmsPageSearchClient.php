<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\SearchClientInterface;

/**
 * @method \Spryker\Client\CmsPageSearch\CmsPageSearchFactory getFactory()
 */
class CmsPageSearchClient extends AbstractClient implements CmsPageSearchClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function search(string $searchString, array $requestParameters = []): array
    {
        $searchClient = $this
            ->getFactory()
            ->getSearchClient();

        $queryExpanderPlugins = $this->getFactory()->getCmsPageSearchQueryExpanderPlugins();

        $searchQuery = $this->buildSearchQuery($searchClient, $searchString, $requestParameters, $queryExpanderPlugins);

        $resultFormatters = $this
            ->getFactory()
            ->getCmsPageSearchResultFormatters();

        return $searchClient->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return int
     */
    public function searchCount(string $searchString, array $requestParameters): int
    {
        $searchClient = $this
            ->getFactory()
            ->getSearchClient();

        $queryExpanderPlugins = $this->getFactory()->getCmsPageSearchCountQueryExpanderPlugins();

        $searchQuery = $this->buildSearchQuery($searchClient, $searchString, $requestParameters, $queryExpanderPlugins);

        return $searchClient->search($searchQuery, [], $requestParameters)->getTotalHits();
    }

    /**
     * @param \Spryker\Client\Search\SearchClientInterface $searchClient
     * @param string $searchString
     * @param array $requestParameters
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[] $queryExpanderPlugins
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function buildSearchQuery(
        SearchClientInterface $searchClient,
        string $searchString,
        array $requestParameters,
        array $queryExpanderPlugins
    ): QueryInterface {
        $searchQuery = $this
            ->getFactory()
            ->createCmsPageSearchQuery($searchString);

        $searchQuery = $searchClient->expandQuery($searchQuery, $queryExpanderPlugins, $requestParameters);

        return $searchQuery;
    }
}
