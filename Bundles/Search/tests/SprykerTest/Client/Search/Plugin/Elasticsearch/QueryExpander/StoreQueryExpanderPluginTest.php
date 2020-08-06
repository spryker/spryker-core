<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\StoreQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group StoreQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class StoreQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @dataProvider storeQueryExpanderDataProvider
     *
     * @param \Elastica\Query $expectedQuery
     *
     * @return void
     */
    public function testStoreQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(Query $expectedQuery): void
    {
        $queryExpander = $this->createStoreQueryExpanderPluginMock();

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin());

        $query = $query->getSearchQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function storeQueryExpanderDataProvider(): array
    {
        return [
            'simple store filtered query' => $this->getDataForSimpleStoreFilteredQuery(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleStoreFilteredQuery(): array
    {
        $expectedQuery = (new Query())
            ->setQuery((new BoolQuery())
                ->addMust((new Match())
                    ->setField(PageIndexMap::STORE, 'AB')));

        return [$expectedQuery];
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\StoreQueryExpanderPlugin|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStoreQueryExpanderPluginMock(): StoreQueryExpanderPlugin
    {
        /** @var \Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\StoreQueryExpanderPlugin|\PHPUnit\Framework\MockObject\MockObject $queryExpander */
        $queryExpander = $this->getMockBuilder(StoreQueryExpanderPlugin::class)
            ->setMethods(['getStore'])
            ->getMock();

        $queryExpander
            ->method('getStore')
            ->willReturn('AB');

        $queryExpander->setFactory($this->getSearchFactory());

        return $queryExpander;
    }
}
