<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Elastica\ResultSet;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class CompletionResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    protected const NAME = 'completion';
    protected const KEY_BUCKETS = 'buckets';
    protected const KEY_KEY = 'key';

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
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): array
    {
        return $this->getCompletionFromSuggests($searchResult);
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return array
     */
    protected function getCompletionFromSuggests(ResultSet $searchResult): array
    {
        $result = [];
        $aggregation = $searchResult->getAggregation(static::NAME);

        foreach ($aggregation[static::KEY_BUCKETS] as $agg) {
            $result[] = $agg[static::KEY_KEY];
        }

        return $result;
    }
}
