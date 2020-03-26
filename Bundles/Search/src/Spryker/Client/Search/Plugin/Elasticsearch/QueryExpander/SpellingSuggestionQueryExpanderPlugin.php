<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\SpellingSuggestionQueryExpanderPlugin` instead.
 *
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SpellingSuggestionQueryExpanderPlugin extends AbstractSuggestionExpanderPlugin
{
    public const SUGGESTION_NAME = 'spelling-suggestion';

    public const SIZE = 1;

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return \Elastica\Suggest\AbstractSuggest|null
     */
    protected function createSuggestion(Query $query, array $requestParameters = [])
    {
        $suggestion = $this->getSuggestion($query);
        if (!$suggestion->hasParam('text') || (string)$suggestion->getParam('text') === '') {
            return null;
        }

        $termSuggest = $this->getFactory()
            ->createSuggestBuilder()
            ->createTerm(static::SUGGESTION_NAME, PageIndexMap::SUGGESTION_TERMS)
            ->setSize(static::SIZE);

        return $termSuggest;
    }
}
