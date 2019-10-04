<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\CompletionQueryExpanderPlugin;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class CompletionResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    protected const NAME = 'completion';

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
        $completions = $this->getCompletionFromSuggests($searchResult);

        return $completions;
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

        foreach ($aggregation['buckets'] as $agg) {
            $result[] = $agg['key'];
        }

        return $result;
    }
}
