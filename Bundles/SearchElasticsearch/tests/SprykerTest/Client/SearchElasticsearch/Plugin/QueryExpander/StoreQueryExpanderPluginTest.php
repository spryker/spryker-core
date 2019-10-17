<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Generated\Shared\Search\PageIndexMap;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\StoreQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
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
        // Arrange
        $queryExpander = $this->createStoreQueryExpanderPluginMock();

        // Act
        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin());

        // Assert
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
     * @return \Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\StoreQueryExpanderPlugin|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStoreQueryExpanderPluginMock(): MockObject
    {
        /** @var \Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\StoreQueryExpanderPlugin|\PHPUnit\Framework\MockObject\MockObject $queryExpander */
        $queryExpander = $this->getMockBuilder(StoreQueryExpanderPlugin::class)
            ->setMethods(['getStoreName'])
            ->getMock();

        $queryExpander
            ->method('getStoreName')
            ->willReturn('AB');

        $queryExpander->setFactory($this->getSearchElasticsearchFactory());

        return $queryExpander;
    }
}
