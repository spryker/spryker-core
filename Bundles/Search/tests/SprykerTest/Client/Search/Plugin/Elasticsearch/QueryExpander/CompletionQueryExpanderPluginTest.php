<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Aggregation\Terms;
use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\CompletionQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group CompletionQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class CompletionQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{
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

        $expectedAggregation = new Terms(CompletionQueryExpanderPlugin::AGGREGATION_NAME);
        $expectedAggregation->setField(PageIndexMap::COMPLETION_TERMS);
        $expectedAggregation->setSize(CompletionQueryExpanderPlugin::SIZE);
        $expectedAggregation->setInclude('');

        $expectedQuery->addAggregation($expectedAggregation);

        return [$expectedQuery];
    }
}
