<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListSearch\Plugin\Search;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ProductListTransfer;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\CustomerCatalog\CustomerCatalogFactory getFactory()
 */
class ProductListQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    protected const REQUEST_PARAM_ID_PRODUCT_LIST = ProductListTransfer::ID_PRODUCT_LIST;

    /**
     * {@inheritDoc}
     * - Expands query with filtering by product list ID.
     *
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        if (!isset($requestParameters[static::REQUEST_PARAM_ID_PRODUCT_LIST])) {
            return $searchQuery;
        }

        $query = $searchQuery->getSearchQuery();
        $this->expandQueryWithProductListFilters($query, $requestParameters[static::REQUEST_PARAM_ID_PRODUCT_LIST]);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param int $idProductList
     *
     * @return void
     */
    protected function expandQueryWithProductListFilters(Query $query, int $idProductList): void
    {
        $boolQuery = $this->getBoolQuery($query);
        $boolQuery->addFilter($this->createProductListBoolQuery($idProductList));
    }

    /**
     * @param int $idProductList
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function createProductListBoolQuery(int $idProductList): BoolQuery
    {
        return (new BoolQuery())
            ->addShould((new Term())->setTerm(PageIndexMap::PRODUCT_LISTS_WHITELISTS, (string)$idProductList))
            ->addShould((new Term())->setTerm(PageIndexMap::PRODUCT_LISTS_BLACKLISTS, (string)$idProductList));
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
                'ProductListFilterQueryExpander is only applicable with %s, got: %s.',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }
}
