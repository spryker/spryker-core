<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Suggest;
use Elastica\Suggest\Term;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SpellingSuggestionQueryExpanderPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group SpellingSuggestionQueryExpanderPluginTest
 */
class SpellingSuggestionQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{

    /**
     * @expectedException \Spryker\Client\Search\Exception\MissingSuggestionQueryException
     *
     * @return void
     */
    public function testCompletionQueryExpanderShouldThrowExceptionWhenBaseQueryDoesntSupportSuggest()
    {
        $baseQueryPlugin = $this->createBaseQueryPlugin();

        $queryExpander = new SpellingSuggestionQueryExpanderPlugin();

        $queryExpander->expandQuery($baseQueryPlugin);
    }

    /**
     * @dataProvider suggestionQueryExpanderDataProvider
     *
     * @param \Elastica\Query $expectedQuery
     *
     * @return void
     */
    public function testSuggestionQueryExpanderShouldExpandTheBaseQueryWithAggregation(Query $expectedQuery)
    {
        $queryExpander = new SpellingSuggestionQueryExpanderPlugin();

        $baseQuery = $this->createBaseQueryPlugin();
        $baseQuery->getSearchQuery()->setSuggest(new Suggest());

        $query = $queryExpander->expandQuery($baseQuery);

        $query = $query->getSearchQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function suggestionQueryExpanderDataProvider()
    {
        return [
            'simple suggestion query' => $this->getDataForSimpleSuggestionQuery(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleSuggestionQuery()
    {
        /** @var \Elastica\Query $expectedQuery */
        $expectedQuery = $this
            ->createBaseQueryPlugin()
            ->getSearchQuery();

        $expectedTermSuggest = new Term(SpellingSuggestionQueryExpanderPlugin::SUGGESTION_NAME, PageIndexMap::SUGGESTION_TERMS);
        $expectedTermSuggest->setSize(SpellingSuggestionQueryExpanderPlugin::SIZE);

        $expectedSuggest = new Suggest();
        $expectedSuggest->addSuggestion($expectedTermSuggest);

        $expectedQuery->setSuggest($expectedSuggest);

        return [$expectedQuery];
    }

}
