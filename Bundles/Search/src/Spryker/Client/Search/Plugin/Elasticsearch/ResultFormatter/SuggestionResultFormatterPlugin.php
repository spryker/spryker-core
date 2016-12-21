<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SuggestionQueryExpanderPlugin;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SuggestionResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    const NAME = 'suggestion';

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $result = [];
        $aggregation = $searchResult->getAggregation(SuggestionQueryExpanderPlugin::AGGREGATION_NAME);

        foreach ($aggregation['buckets'] as $agg) {
            $result[] = $agg['key'];
        }

        return $result;
    }

}
