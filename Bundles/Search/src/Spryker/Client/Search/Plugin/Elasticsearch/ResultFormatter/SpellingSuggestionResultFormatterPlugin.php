<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SpellingSuggestionQueryExpanderPlugin;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\SpellingSuggestionResultFormatterPlugin` instead.
 *
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SpellingSuggestionResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'spellingSuggestion';

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
     * @return string|null
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $suggests = $searchResult->getSuggests();
        $spellingSuggestion = $this->extractSpellingSuggestion($suggests);

        return $spellingSuggestion;
    }

    /**
     * @param array $suggests
     *
     * @return string|null
     */
    protected function extractSpellingSuggestion(array $suggests)
    {
        if (!isset($suggests[SpellingSuggestionQueryExpanderPlugin::SUGGESTION_NAME])) {
            return null;
        }

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

        if (!$suggest) {
            return null;
        }

        return implode(' ', $suggestionParts);
    }
}
