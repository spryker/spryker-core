<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Type;
use Generated\Shared\Search\ProductReviewIndexMap;
use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\NamedQueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Shared\ProductReview\ProductReviewConfig;

/**
 * @method \Spryker\Client\ProductReview\ProductReviewConfig getFactory()
 */
class ProductReviewsQueryPlugin extends AbstractPlugin implements QueryInterface, NamedQueryInterface
{
    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer
     */
    protected $productReviewSearchRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     */
    public function __construct(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer)
    {
        $this->productReviewSearchRequestTransfer = $productReviewSearchRequestTransfer;
        $this->query = $this->createSearchQuery();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getIndexName(): string
    {
        return 'product-review';
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery()
    {
        $productReviewTypeFilter = $this->createProductReviewTypeFilter();
        $productReviewsFilter = $this->createProductReviewsFilter();

        $boolQuery = new BoolQuery();
        $boolQuery
            ->addFilter($productReviewTypeFilter)
            ->addFilter($productReviewsFilter);

        $query = $this->createQuery($boolQuery);

        return $query;
    }

    /**
     * @return \Elastica\Query\Match
     */
    protected function createProductReviewsFilter()
    {
        $this->productReviewSearchRequestTransfer->requireIdProductAbstract();

        $productReviewsFilter = new Match();
        $productReviewsFilter->setField(ProductReviewIndexMap::ID_PRODUCT_ABSTRACT, $this->productReviewSearchRequestTransfer->getIdProductAbstract());

        return $productReviewsFilter;
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
     * @return \Elastica\Query\Type
     */
    protected function createProductReviewTypeFilter()
    {
        $productReviewTypeFilter = new Type();
        $productReviewTypeFilter->setType(ProductReviewConfig::ELASTICSEARCH_INDEX_TYPE_NAME);

        return $productReviewTypeFilter;
    }
}
