<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SpellingSuggestionQueryExpanderPlugin;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SpellingSuggestionResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    const NAME = 'spellingSuggestion';

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
        $suggest = false;
        $suggestionParts = [];

        foreach ($suggests[SpellingSuggestionQueryExpanderPlugin::SUGGESTION_NAME] as $item) {
            if ($item['options']) {
                $suggest = true;
                $suggestionParts[] = $item['options'][0]['text'];
                continue;
            }

            $suggestionParts[] = $item['text'];
        }

        if ($suggest) {
            return implode(' ', $suggestionParts);
        }

        return null;
    }

}
