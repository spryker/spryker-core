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
use Spryker\Client\SearchElasticsearch\Config\SortConfig;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\SortedQueryExpanderPlugin;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;

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
    /**
     * @dataProvider sortedQueryExpanderDataProvider
     *
     * @param \Spryker\Client\SearchExtension\Config\SortConfigInterface $sortConfig
     * @param \Elastica\Query $expectedQuery
     * @param array $params
     *
     * @return void
     */
    public function testSortedQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(
        SortConfigInterface $sortConfig,
        Query $expectedQuery,
        array $params = []
    ): void {
        $searchFactoryMock = $this->createSearchElasticsearchFactoryMockWithSortConfig($sortConfig);

        $queryExpander = new SortedQueryExpanderPlugin();
        $queryExpander->setFactory($searchFactoryMock);

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin(), $params);

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
        $sortConfig = $this->createSimpleSortSearchConfig();

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery());

        $requestParameters = [];

        return [$sortConfig, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleStringSort(): array
    {
        $sortConfig = $this->createSimpleSortSearchConfig();

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery())
            ->addSort(
                [
                    PageIndexMap::STRING_SORT . '.foo' => [
                        'order' => SortConfig::DIRECTION_ASC,
                        'mode' => 'min',
                    ],
                ]
            );

        $requestParameters = [
            SortConfig::DEFAULT_SORT_PARAM_KEY => 'foo-param',
        ];

        return [$sortConfig, $expectedQuery, $requestParameters];
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
     * @return \Spryker\Client\SearchExtension\Config\SortConfigInterface
     */
    protected function createSimpleSortSearchConfig(): SortConfigInterface
    {
        $sortConfig = $this->createSortConfig();
        $sortConfig->addSort(
            (new SortConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_SORT)
        );

        return $sortConfig;
    }
}
