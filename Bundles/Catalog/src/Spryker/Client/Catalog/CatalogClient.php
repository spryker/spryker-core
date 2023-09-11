<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class CatalogClient extends AbstractClient implements CatalogClientInterface
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
    public function catalogSearch($searchString, array $requestParameters = [])
    {
        $searchQuery = $this
            ->getFactory()
            ->createCatalogSearchQuery($searchString);

        $searchQuery = $this
            ->getFactory()
            ->getSearchClient()
            ->expandQuery(
                $searchQuery,
                $this->getFactory()->getCatalogSearchQueryExpanderPlugins($searchQuery),
                $requestParameters,
            );

        $resultFormatters = $this
            ->getFactory()
            ->getCatalogSearchResultFormatters($searchQuery);

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
     * @return array<string, mixed>
     */
    public function catalogSuggestSearch($searchString, array $requestParameters = [])
    {
        $searchQuery = $this
            ->getFactory()
            ->createSuggestSearchQuery($searchString);

        $searchQuery = $this
            ->getFactory()
            ->getSearchClient()
            ->expandQuery($searchQuery, $this->getFactory()->getSuggestionQueryExpanderPlugins($searchQuery), $requestParameters);

        $resultFormatters = $this
            ->getFactory()
            ->getSuggestionResultFormatters($searchQuery);

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getCatalogViewMode(Request $request)
    {
        return $this->getFactory()
            ->createCatalogViewModePersistence()
            ->getViewMode($request);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $mode
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setCatalogViewMode($mode, Response $response)
    {
        return $this->getFactory()
            ->createCatalogViewModePersistence()
            ->setViewMode($mode, $response);
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
    public function catalogSearchCount(string $searchString, array $requestParameters): int
    {
        $searchClient = $this
            ->getFactory()
            ->getSearchClient();
        $searchQuery = $this
            ->getFactory()
            ->createCatalogSearchQuery($searchString);
        $searchQuery = $searchClient
            ->expandQuery($searchQuery, $this->getFactory()->getCatalogSearchCountQueryExpanderPlugins($searchQuery), $requestParameters);
        $searchResult = $searchClient->search($searchQuery, [], $requestParameters);

        foreach ($this->getFactory()->getSearchResultCountPlugins() as $searchResultCountPlugin) {
            $totalCount = $searchResultCountPlugin->findTotalCount($searchResult);

            if ($totalCount !== null) {
                return $totalCount;
            }
        }

        return $searchResult->getTotalHits();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return \Elastica\ResultSet|array
     */
    public function searchProductConcretesByFullText(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer)
    {
        $searchQuery = $this->getFactory()->createProductConcreteCatalogSearchQuery((string)$productConcreteCriteriaFilterTransfer->getSearchString());
        $requestParameters = $productConcreteCriteriaFilterTransfer->getRequestParams();
        $requestParameters[$this->getFactory()->getConfig()->getItemsPerPageParameterName()] = $productConcreteCriteriaFilterTransfer->getLimit();
        $searchQuery = $this
            ->getFactory()
            ->getSearchClient()
            ->expandQuery(
                $searchQuery,
                $this->getFactory()->getProductConcreteCatalogSearchQueryExpanderPlugins($searchQuery),
                $requestParameters,
            );
        $resultFormatters = $this
            ->getFactory()
            ->getProductConcreteCatalogSearchResultFormatters($searchQuery);

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }
}
