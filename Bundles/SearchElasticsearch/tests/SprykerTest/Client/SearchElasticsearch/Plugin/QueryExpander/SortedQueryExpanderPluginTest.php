<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface;
use Spryker\Client\SearchElasticsearch\Config\SortConfig;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\SortedQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group QueryExpander
 * @group SortedQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class SortedQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{
    protected const DIRECTION_ASC = 'asc';

    /**
     * @dataProvider sortedQueryExpanderDataProvider
     *
     * @param \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface $searchConfigMock
     * @param \Elastica\Query $expectedQuery
     * @param array $params
     *
     * @return void
     */
    public function testSortedQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(
        SearchConfigInterface $searchConfigMock,
        Query $expectedQuery,
        array $params = []
    ): void {
        // Arrange
        $searchFactoryMock = $this->createSearchFactoryMockedWithSearchConfig($searchConfigMock);
        $queryExpander = new SortedQueryExpanderPlugin();
        $queryExpander->setFactory($searchFactoryMock);

        // Act
        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin(), $params);

        // Assert
        $query = $query->getSearchQuery();
        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function sortedQueryExpanderDataProvider(): array
    {
        return [
            'no sorting' => $this->getDataWithoutSorting(),
            'single string sorting' => $this->getDataForSimpleStringSort(),
            'invalid parameter sorting' => $this->getDataForInvalidParameterSort(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithoutSorting(): array
    {
        $searchConfigMock = $this->createSimpleSortSearchConfig();

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery());

        $requestParameters = [];

        return [$searchConfigMock, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleStringSort(): array
    {
        $searchConfigMock = $this->createSimpleSortSearchConfig();

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery())
            ->addSort(
                [
                    PageIndexMap::STRING_SORT . '.foo' => [
                        'order' => static::DIRECTION_ASC,
                        'mode' => 'min',
                    ],
                ]
            );

        $requestParameters = [
            SortConfig::DEFAULT_SORT_PARAM_KEY => 'foo-param',
        ];

        return [$searchConfigMock, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForInvalidParameterSort(): array
    {
        $searchConfig = $this->createSimpleSortSearchConfig();

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery());

        $requestParameters = [
            SortConfig::DEFAULT_SORT_PARAM_KEY => 'bar',
        ];

        return [$searchConfig, $expectedQuery, $requestParameters];
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createSimpleSortSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getSortConfig()
            ->addSort(
                (new SortConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_SORT)
            );

        return $searchConfigMock;
    }
}
