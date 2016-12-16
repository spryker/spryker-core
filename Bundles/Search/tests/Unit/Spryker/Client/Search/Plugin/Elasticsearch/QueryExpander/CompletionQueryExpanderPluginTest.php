<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Suggest;
use Elastica\Suggest\Completion;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\CompletionQueryExpanderPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group CompletionQueryExpanderPluginTest
 */
class CompletionQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{

    /**
     * @expectedException \Spryker\Client\Search\Exception\MissingSuggestionQueryException
     *
     * @return void
     */
    public function testCompletionQueryExpanderShouldThrowExceptionWhenBaseQueryDoesntSupportSuggest()
    {
        $baseQueryPlugin = $this->createBaseQueryPlugin();

        $queryExpander = new CompletionQueryExpanderPlugin();

        $queryExpander->expandQuery($baseQueryPlugin);
    }

    /**
     * @dataProvider CompletionQueryExpanderDataProvider
     *
     * @param \Elastica\Query $expectedQuery
     *
     * @return void
     */
    public function testCompletionQueryExpanderShouldExpandTheBaseQueryWithAggregation(Query $expectedQuery)
    {
        $baseQueryPlugin = $this->createBaseQueryPlugin();
        $baseQueryPlugin->getSearchQuery()->setSuggest(new Suggest());

        $queryExpander = new CompletionQueryExpanderPlugin();

        $query = $queryExpander->expandQuery($baseQueryPlugin);

        $query = $query->getSearchQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function completionQueryExpanderDataProvider()
    {
        return [
            'simple completion query' => $this->getDataForSimpleCompletionQuery(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleCompletionQuery()
    {
        /** @var \Elastica\Query $expectedQuery */
        $expectedQuery = $this
            ->createBaseQueryPlugin()
            ->getSearchQuery();

        $expectedCompletion = new Completion(CompletionQueryExpanderPlugin::AGGREGATION_NAME, PageIndexMap::COMPLETION_TERMS);
        $expectedCompletion->setSize(CompletionQueryExpanderPlugin::SIZE);

        $suggest = new Suggest();
        $suggest->addSuggestion($expectedCompletion);

        $expectedQuery->setSuggest($suggest);

        return [$expectedQuery];
    }

}
