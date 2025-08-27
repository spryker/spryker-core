<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Client\CmsPageSearch;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchTypeIdentifierInterface;

/**
 * @method \Spryker\Client\CmsPageSearch\CmsPageSearchFactory getFactory()
 */
class CmsPageSearchClient extends AbstractClient implements CmsPageSearchClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchString
     * @param array<string, mixed> $requestParameters
     *
     * @return array
     */
    public function search(string $searchString, array $requestParameters = []): array
    {
        $searchStrategy = null;

        $searchQuery = $this->getFactory()->createCmsPageSearchQuery($searchString);
        if ($searchQuery instanceof SearchTypeIdentifierInterface) {
            $searchStrategy = $searchQuery->getSearchType();
        }

        $queryExpanderPlugins = $this->getFactory()->createCmsPageSearchQueryExpanderPluginsStrategyResolver()->get($searchStrategy);

        $searchQuery = $this->getFactory()->getSearchClient()->expandQuery($searchQuery, $queryExpanderPlugins, $requestParameters);

        $resultFormatters = $this
            ->getFactory()
            ->createCmsPageSearchResultFormattersStrategyResolver()
            ->get($searchStrategy);

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchString
     * @param array<string, mixed> $requestParameters
     *
     * @return int
     */
    public function searchCount(string $searchString, array $requestParameters): int
    {
        $searchStrategy = null;

        $searchQuery = $this->getFactory()->createCmsPageSearchQuery($searchString);
        if ($searchQuery instanceof SearchTypeIdentifierInterface) {
            $searchStrategy = $searchQuery->getSearchType();
        }

        $queryExpanderPlugins = $this->getFactory()->createCmsPageSearchCountQueryExpanderPluginsStrategyResolver()->get($searchStrategy);

        $searchQuery = $this->getFactory()->getSearchClient()->expandQuery($searchQuery, $queryExpanderPlugins, $requestParameters);

        $searchResult = $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, [], $requestParameters);

        $searchResultCountPlugin = $this->getFactory()->getSearchResultCountPlugins()[$searchStrategy] ?? null;

        if ($searchResultCountPlugin === null) {
            return $searchResult->getTotalHits();
        }

        return $searchResultCountPlugin->findTotalCount($searchResult) ?? 0;
    }
}
