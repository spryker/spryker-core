<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\SuggestionByTypeQueryExpanderPlugin;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class SuggestionByTypeResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'suggestionByType';

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
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): array
    {
        $result = [];
        $aggregation = $searchResult->getAggregation(SuggestionByTypeQueryExpanderPlugin::AGGREGATION_NAME);

        foreach ($aggregation['buckets'] as $agg) {
            $type = $agg['key'];

            foreach ($agg[SuggestionByTypeQueryExpanderPlugin::NESTED_AGGREGATION_NAME]['hits']['hits'] as $hit) {
                $result[$type][] = $hit['_source'][PageIndexMap::SEARCH_RESULT_DATA];
            }
        }

        return $result;
    }
}
