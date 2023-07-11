<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewSearch\Plugin\Search;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Generated\Shared\Transfer\ProductReviewTransfer;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\ProductReviewSearch\ProductReviewSearchFactory getFactory()
 */
class FilterByIdProductReviewQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_PRODUCT_REVIEW = ProductReviewTransfer::ID_PRODUCT_REVIEW;

    /**
     * @var string
     */
    protected const SEARCH_FIELD_ID = '_id';

    /**
     * {@inheritDoc}
     * - Expands the query using a filter by document _id.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        if (!isset($requestParameters[static::REQUEST_PARAM_ID_PRODUCT_REVIEW])) {
            return $searchQuery;
        }

        $this->expandQueryWithProductReviewFilter(
            $searchQuery->getSearchQuery(),
            $requestParameters[static::REQUEST_PARAM_ID_PRODUCT_REVIEW],
        );

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param string $idProductReview
     *
     * @return void
     */
    protected function expandQueryWithProductReviewFilter(Query $query, string $idProductReview): void
    {
        $boolQuery = $this->getBoolQuery($query);
        $boolQuery->addFilter($this->createIdProductReviewBoolQuery($idProductReview));
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \Spryker\Zed\ProductStorage\Exception\InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query): BoolQuery
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s is only applicable with %s.',
                    static::class,
                    BoolQuery::class,
                ),
            );
        }

        return $boolQuery;
    }

    /**
     * @param string $idProductReview
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function createIdProductReviewBoolQuery(string $idProductReview): BoolQuery
    {
        $filter = (new Term())
            ->setTerm(
                static::SEARCH_FIELD_ID,
                $this->getFactory()->createProductReviewKeyBuilder()->buildKey($idProductReview),
            );

        return (new BoolQuery())->addFilter($filter);
    }
}
