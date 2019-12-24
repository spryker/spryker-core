<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use Elastica\Query\Type;
use Generated\Shared\Search\ProductReviewIndexMap;
use Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Shared\ProductReview\ProductReviewConfig;

/**
 * @method \Spryker\Client\ProductReview\ProductReviewConfig getFactory()
 */
class BulkProductReviewsQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    protected const SOURCE_IDENTIFIER = 'product-review';

    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer
     */
    protected $bulkProductReviewSearchRequestTransfer;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected $searchContextTransfer;

    /**
     * @param \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer $bulkProductReviewSearchRequestTransfer
     */
    public function __construct(BulkProductReviewSearchRequestTransfer $bulkProductReviewSearchRequestTransfer)
    {
        $this->bulkProductReviewSearchRequestTransfer = $bulkProductReviewSearchRequestTransfer;
        $this->query = $this->createSearchQuery();
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     * - Defines context for bulk product review search.
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
     * - Sets context for bulk product review search.
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
     * @return \Elastica\Query
     */
    protected function createSearchQuery(): Query
    {
        $productReviewTypeFilter = $this->createProductReviewTypeFilter();
        $productReviewsFilter = $this->createProductReviewsFilter();

        $boolQuery = (new BoolQuery())
            ->addFilter($productReviewTypeFilter)
            ->addFilter($productReviewsFilter);

        $query = (new Query())
            ->setQuery($boolQuery)
            ->setSource([ProductReviewIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @return \Elastica\Query\Terms
     */
    protected function createProductReviewsFilter(): Terms
    {
        $this->bulkProductReviewSearchRequestTransfer->requireProductAbstractIds();

        $productReviewsFilter = new Terms();
        $productReviewsFilter->setTerms(
            ProductReviewIndexMap::ID_PRODUCT_ABSTRACT,
            $this->bulkProductReviewSearchRequestTransfer->getProductAbstractIds()
        );

        return $productReviewsFilter;
    }

    /**
     * @return \Elastica\Query\Type
     */
    protected function createProductReviewTypeFilter(): Type
    {
        $productReviewTypeFilter = new Type();
        $productReviewTypeFilter->setType(ProductReviewConfig::ELASTICSEARCH_INDEX_TYPE_NAME);

        return $productReviewTypeFilter;
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
     * @return bool
     */
    protected function hasSearchContext(): bool
    {
        return (bool)$this->searchContextTransfer;
    }
}
