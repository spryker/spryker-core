<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Plugin\Elasticsearch\Query;

use Codeception\Test\Unit;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\SortedServicePointSearchQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointSearch
 * @group Plugin
 * @group Elasticsearch
 * @group Query
 * @group SortedServicePointSearchQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class SortedServicePointSearchQueryExpanderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query::PARAMETER_SORT
     *
     * @var string
     */
    protected const PARAMETER_SORT = 'sort';

    /**
     * @uses \Spryker\Client\ServicePointSearch\ServicePointSearchConfig::SORT_PARAMETER_CITY_DESC
     *
     * @var string
     */
    protected const SORT_PARAMETER_CITY_DESC = 'city_desc';

    /**
     * @var array
     */
    protected const ELASTIC_PARAMETER_SORT_DESC = [[
        'string-sort.city' => [
            'order' => 'desc',
        ],
    ]];

    /**
     * @var array
     */
    protected const ELASTIC_PARAMETER_SORT_ASC = [[
        'string-sort.city' => [
            'order' => 'asc',
        ],
    ]];

    /**
     * @var \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\SortedServicePointSearchQueryExpanderPlugin
     */
    protected SortedServicePointSearchQueryExpanderPlugin $sortedServicePointSearchQueryExpanderPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sortedServicePointSearchQueryExpanderPlugin = new SortedServicePointSearchQueryExpanderPlugin();
    }

    /**
     * @return void
     */
    public function testExpandQueryShouldNotExpandWhileAlreadySortedByScore(): void
    {
        // Arrange
        $abstractQuery = new BoolQuery();
        $query = $this->createQueryMock($abstractQuery, 'searchString');

        $requestParameters = [
            static::PARAMETER_SORT => static::SORT_PARAMETER_CITY_DESC,
        ];

        // Act
        $sort = $this->sortedServicePointSearchQueryExpanderPlugin
            ->expandQuery($query, $requestParameters)
            ->getSearchQuery();

        // Assert
        $this->assertFalse($sort->hasParam(static::PARAMETER_SORT));
    }

    /**
     * @return void
     */
    public function testExpandQueryShouldExpandWithDefaultSortingWhileSortingIsUnknown(): void
    {
        // Arrange
        $abstractQuery = new BoolQuery();
        $query = $this->createQueryMock($abstractQuery);

        $requestParameters = [
            static::PARAMETER_SORT => 'unknown',
        ];

        // Act
        $sort = $this->sortedServicePointSearchQueryExpanderPlugin
            ->expandQuery($query, $requestParameters)
            ->getSearchQuery()
            ->getParam(static::PARAMETER_SORT);

        // Assert
        $this->assertSame(static::ELASTIC_PARAMETER_SORT_ASC, $sort);
    }

    /**
     * @return void
     */
    public function testExpandQueryShouldExpandWithSorting(): void
    {
        // Arrange
        $abstractQuery = new BoolQuery();
        $query = $this->createQueryMock($abstractQuery);

        $requestParameters = [
            static::PARAMETER_SORT => static::SORT_PARAMETER_CITY_DESC,
        ];

        // Act
        $sort = $this->sortedServicePointSearchQueryExpanderPlugin
            ->expandQuery($query, $requestParameters)
            ->getSearchQuery()
            ->getParam(static::PARAMETER_SORT);

        // Assert
        $this->assertSame(static::ELASTIC_PARAMETER_SORT_DESC, $sort);
    }

    /**
     * @param \Elastica\Query\AbstractQuery $abstractQuery
     * @param string|null $searchString
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQueryMock(AbstractQuery $abstractQuery, ?string $searchString = null): QueryInterface
    {
        $queryMock = $this->getMockBuilder(QueryInterface::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->addMethods(['getSearchString'])
            ->onlyMethods(['getSearchQuery'])
            ->getMock();

        $queryMock->method('getSearchString')->willReturn($searchString);
        $queryMock->method('getSearchQuery')->willReturn(new Query($abstractQuery));

        return $queryMock;
    }
}
