<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\ProductReview\Aggregation\BatchRatingAggregation;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

/**
 * @method \Spryker\Client\ProductReview\ProductReviewFactory getFactory()
 */
class ProductRatingAggregationBatchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    protected const NAME = 'productAggregation';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        return $this->extractRatingAggregation($searchResult);
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return array
     */
    protected function extractRatingAggregation(ResultSet $searchResult)
    {
        $aggregation = $searchResult->getAggregation(BatchRatingAggregation::PRODUCT_AGGREGATOIN_NAME);

        return $this->getFactory()->createProductRatingAggreagationResultFormatter()->formatBatch($aggregation);
    }
}
