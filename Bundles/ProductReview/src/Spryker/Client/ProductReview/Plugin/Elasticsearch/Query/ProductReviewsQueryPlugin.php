<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin\Elasticsearch\Query;

use Elastica\Index;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Elastica\Query\Type;
use Generated\Shared\Search\ProductReviewIndexMap;
use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Shared\ProductReview\ProductReviewConfig;

/**
 * @method \Spryker\Client\ProductReview\ProductReviewConfig getFactory()
 */
class ProductReviewsQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    protected const SOURCE_IDENTIFIER = 'product-review';

    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer
     */
    protected $productReviewSearchRequestTransfer;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected $searchContextTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     */
    public function __construct(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer)
    {
        $this->productReviewSearchRequestTransfer = $productReviewSearchRequestTransfer;
        $this->query = $this->createSearchQuery();
    }

    /**
     * {@inheritDoc}
     * - Returns a query object for product review search.
     *
     * @api
     *
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     * - Defines a context for product review search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        if (!$this->hasSearchContext()) {
            $this->setupDefaultSearchContext();
        }

        return $this->searchContextTransfer;
    }

    /**
     * {@inheritDoc}
     * - Sets a context for product review search.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * @return void
     */
    protected function setupDefaultSearchContext(): void
    {
        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setSourceIdentifier(static::SOURCE_IDENTIFIER);

        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery()
    {
        $boolQuery = new BoolQuery();
        $boolQuery = $this->addProductReviewTypeFilterToQuery($boolQuery);
        $boolQuery = $this->addProductReviewsFilterToQuery($boolQuery);

        $query = $this->createQuery($boolQuery);

        return $query;
    }

    /**
     * @param \Elastica\Query\BoolQuery $query
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function addProductReviewsFilterToQuery(BoolQuery $query): BoolQuery
    {
        $this->productReviewSearchRequestTransfer->requireIdProductAbstract();

        $productReviewsFilter = $this->getMatchQuery()
            ->setField(ProductReviewIndexMap::ID_PRODUCT_ABSTRACT, $this->productReviewSearchRequestTransfer->getIdProductAbstract());
        $query->addFilter($productReviewsFilter);

        return $query;
    }

    /**
     * @param \Elastica\Query\AbstractQuery $abstractQuery
     *
     * @return \Elastica\Query
     */
    protected function createQuery(AbstractQuery $abstractQuery)
    {
        $query = new Query();
        $query
            ->setQuery($abstractQuery)
            ->setSource([ProductReviewIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param \Elastica\Query\BoolQuery $query
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function addProductReviewTypeFilterToQuery(BoolQuery $query): BoolQuery
    {
        if (!$this->supportsMappingTypes()) {
            return $query;
        }

        $productReviewTypeFilter = new Type();
        $productReviewTypeFilter->setType(ProductReviewConfig::ELASTICSEARCH_INDEX_TYPE_NAME);
        $query->addFilter($productReviewTypeFilter);

        return $query;
    }

    /**
     * @return bool
     */
    protected function hasSearchContext(): bool
    {
        return (bool)$this->searchContextTransfer;
    }

    /**
     * @return bool
     */
    protected function supportsMappingTypes(): bool
    {
        return method_exists(Index::class, 'getType');
    }

    /**
     * For compatibility with PHP 8.
     *
     * @return \Elastica\Query\MatchQuery|\Elastica\Query\Match
     */
    protected function getMatchQuery()
    {
        $matchQueryClassName = class_exists(MatchQuery::class)
            ? MatchQuery::class
            : '\Elastica\Query\Match';

        return new $matchQueryClassName();
    }
}
