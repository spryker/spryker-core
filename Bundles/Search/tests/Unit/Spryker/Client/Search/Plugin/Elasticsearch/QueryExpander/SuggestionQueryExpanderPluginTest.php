<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Aggregation\Terms;
use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SuggestionQueryExpanderPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group SuggestionQueryExpanderPluginTest
 */
class SuggestionQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{

    /**
     * @dataProvider suggestionQueryExpanderDataProvider
     *
     * @param \Elastica\Query $expectedQuery
     *
     * @return void
     */
    public function testSuggestionQueryExpanderShouldExpandTheBaseQueryWithAggregation(Query $expectedQuery)
    {
        $queryExpander = new SuggestionQueryExpanderPlugin();

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin());

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

        $expectedAggregation = new Terms(SuggestionQueryExpanderPlugin::AGGREGATION_NAME);
        $expectedAggregation
            ->setField(PageIndexMap::SUGGESTION_TERMS)
            ->setSize(SuggestionQueryExpanderPlugin::SIZE);

        $expectedQuery->addAggregation($expectedAggregation);

        return [$expectedQuery];
    }

}
