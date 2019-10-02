<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Aggregation\Terms;
use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\CompletionQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
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
    public function testCompletionQueryExpanderShouldExpandTheBaseQueryWithAggregation(Query $expectedQuery): void
    {
        // Arrange
        $baseQueryPlugin = $this->createBaseQueryPlugin();
        $queryExpander = new CompletionQueryExpanderPlugin();

        // Act
        $query = $queryExpander->expandQuery($baseQueryPlugin);

        // Assert
        $query = $query->getSearchQuery();
        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function completionQueryExpanderDataProvider(): array
    {
        return [
            'simple completion query' => $this->getDataForSimpleCompletionQuery(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleCompletionQuery(): array
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
