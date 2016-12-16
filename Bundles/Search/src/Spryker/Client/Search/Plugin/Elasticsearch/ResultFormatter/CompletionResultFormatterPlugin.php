<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\CompletionQueryExpanderPlugin;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class CompletionResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    const NAME = 'completion';

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
        $suggests = $searchResult->getSuggests();
        $completions = $this->getCompletionFromSuggests($suggests);

        return $completions;
    }

    /**
     * @param array $suggests
     *
     * @return array
     */
    protected function getCompletionFromSuggests(array $suggests)
    {
        $result = [];

        if (!isset($suggests[CompletionQueryExpanderPlugin::AGGREGATION_NAME][0]['options'])) {
            return $result;
        }

        foreach ($suggests[CompletionQueryExpanderPlugin::AGGREGATION_NAME][0]['options'] as $option) {
            $result[] = $option['text'];
        }

        return $result;
    }

}
