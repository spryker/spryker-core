<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Search\PageIndexMap;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class StoreQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * {@inheritdoc}
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
        $this->addStoreFilterToQuery($searchQuery->getSearchQuery());

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function addStoreFilterToQuery(Query $query): void
    {
        $boolQuery = $this->getBoolQuery($query);

        $matchQuery = $this->getFactory()
            ->createQueryBuilder()
            ->createMatchQuery()
            ->setField(PageIndexMap::STORE, $this->getStore());

        $boolQuery->addMust($matchQuery);
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query): BoolQuery
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Localized query expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }

    /**
     * @return string
     */
    protected function getStore(): string
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
