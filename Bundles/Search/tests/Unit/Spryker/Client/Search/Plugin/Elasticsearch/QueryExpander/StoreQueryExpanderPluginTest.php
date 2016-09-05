<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\StoreQueryExpanderPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group StoreQueryExpanderPluginTest
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
    public function testStoreQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(Query $expectedQuery)
    {
        /** @var \Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\LocalizedQueryExpanderPlugin|\PHPUnit_Framework_MockObject_MockObject $queryExpander */
        $queryExpander = $this->getMockBuilder(StoreQueryExpanderPlugin::class)
            ->setMethods(['getStore'])
            ->getMock();

        $queryExpander
            ->method('getStore')
            ->willReturn('AB');

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin());

        $query = $query->getSearchQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function storeQueryExpanderDataProvider()
    {
        return [
            'simple store filtered query' => $this->getDataForSimpleStoreFilteredQuery(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleStoreFilteredQuery()
    {
        $expectedQuery = (new Query())
            ->setQuery((new BoolQuery())
                ->addMust((new Match())
                    ->setField(PageIndexMap::STORE, 'AB')));

        return [$expectedQuery];
    }

}
