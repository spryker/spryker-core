<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringGetterInterface;
use Spryker\Client\Search\Exception\InvalidSearchQueryException;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class CompletionQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    public const AGGREGATION_NAME = 'completion';

    public const SIZE = 10;

    public const SEARCH_WILDCARD = '.*';

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $searchQuery = $this->assertSearchStringGetterQuery($searchQuery);

        $query = $searchQuery->getSearchQuery();
        $this->addAggregation($query, $searchQuery->getSearchString());

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @throws \Spryker\Client\Search\Exception\InvalidSearchQueryException
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\Search\Dependency\Plugin\SearchStringGetterInterface
     */
    protected function assertSearchStringGetterQuery(QueryInterface $searchQuery)
    {
        if (!$searchQuery instanceof SearchStringGetterInterface) {
            throw new InvalidSearchQueryException(sprintf(
                'The base search query must implement %s in order to use %s.',
                SearchStringGetterInterface::class,
                static::class
            ));
        }

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param string $searchString
     *
     * @return void
     */
    protected function addAggregation(Query $query, $searchString)
    {
        $termsAggregation = $this->getFactory()
            ->createAggregationBuilder()
            ->createTermsAggregation(static::AGGREGATION_NAME)
            ->setField(PageIndexMap::COMPLETION_TERMS)
            ->setSize(static::SIZE)
            ->setInclude($this->getRegexpQueryString($searchString));

        $query->addAggregation($termsAggregation);
    }

    /**
     * @param string $searchString
     *
     * @return string
     */
    protected function getRegexpQueryString($searchString)
    {
        $searchString = mb_strtolower($searchString);

        /*
         * Split the text by whitespace and add double-quotes around them to interpret them literally.
         * Double quotes inside the search string has to be outside of the literally interpreted search string.
         */
        $searchString = str_replace('"', '"\\""', $searchString);
        $searchString = preg_replace('/\s+/', '"' . static::SEARCH_WILDCARD . ' "', $searchString);

        if ($searchString) {
            return static::SEARCH_WILDCARD . '"' . $searchString . '"' . static::SEARCH_WILDCARD;
        }

        return '';
    }
}
