<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\QueryExpander\BatchRatingAggregationQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

/**
 * @method \Spryker\Client\ProductReview\ProductReviewFactory getFactory()
 */
class RatingAggregationBatchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'productAggregation';
    public const SUB_NAME = 'ratingAggregation';

    /**
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
        $result = $this->extractRatingAggregation($searchResult);
        $result = $this->sortResults($result);

        return $result;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return array
     */
    protected function extractRatingAggregation(ResultSet $searchResult)
    {
        $result = [];
        $aggregation = $searchResult->getAggregation(BatchRatingAggregationQueryExpanderPlugin::AGGREGATION_NAME);

        foreach ($aggregation['buckets'] as $bucket) {
            $result[$bucket['key']] = [
                static::SUB_NAME => [],
            ];

            $ratingAggregation = $bucket['rating-aggregation'];

            foreach ($ratingAggregation['buckets'] as $subBucket) {
                $result[$bucket['key']][static::SUB_NAME][$subBucket['key']] = $subBucket['doc_count'];
            }
        }

        return $result;
    }

    /**
     * @param array $result
     *
     * @return array
     */
    protected function sortResults(array $result)
    {
        krsort($result);

        return $result;
    }
}
