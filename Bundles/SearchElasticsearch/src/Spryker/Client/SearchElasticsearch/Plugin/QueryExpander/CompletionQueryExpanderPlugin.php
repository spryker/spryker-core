<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchElasticsearch\Exception\InvalidSearchQueryException;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringGetterInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class CompletionQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    protected const AGGREGATION_NAME = 'completion';
    protected const SIZE = 10;
    protected const SEARCH_WILDCARD = '.*';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $searchQuery = $this->assertSearchStringGetterQuery($searchQuery);

        $query = $searchQuery->getSearchQuery();
        $this->addAggregation($query, $searchQuery->getSearchString());

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @throws \Spryker\Client\SearchElasticsearch\Exception\InvalidSearchQueryException
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringGetterInterface
     */
    protected function assertSearchStringGetterQuery(QueryInterface $searchQuery): QueryInterface
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
     * @param string|null $searchString
     *
     * @return void
     */
    protected function addAggregation(Query $query, ?string $searchString): void
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
     * @param string|null $searchString
     *
     * @return string
     */
    protected function getRegexpQueryString(?string $searchString): string
    {
        $searchString = mb_strtolower($searchString);
        $searchString = str_replace('"', '"\\""', $searchString);
        $searchString = preg_replace('/\s+/', sprintf('"%s"', static::SEARCH_WILDCARD), $searchString);

        if ($searchString) {
            return sprintf('%s"%s"%s', static::SEARCH_WILDCARD, $searchString, static::SEARCH_WILDCARD);
        }

        return '';
    }
}
