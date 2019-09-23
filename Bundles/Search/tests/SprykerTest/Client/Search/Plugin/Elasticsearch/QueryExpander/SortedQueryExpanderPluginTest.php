<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Config\SortConfigBuilder;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SortedQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group SortedQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class SortedQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @dataProvider sortedQueryExpanderDataProvider
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     * @param \Elastica\Query $expectedQuery
     * @param array $params
     *
     * @return void
     */
    public function testSortedQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(SearchConfigInterface $searchConfig, Query $expectedQuery, array $params = [])
    {
        $searchFactoryMock = $this->createSearchFactoryMockedWithSearchConfig($searchConfig);

        $queryExpander = new SortedQueryExpanderPlugin();
        $queryExpander->setFactory($searchFactoryMock);

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin(), $params);

        $query = $query->getSearchQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function sortedQueryExpanderDataProvider()
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
    protected function getDataWithoutSorting()
    {
        $searchConfig = $this->createSimpleSortSearchConfig();

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery());

        $requestParameters = [];

        return [$searchConfig, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleStringSort()
    {
        $searchConfig = $this->createSimpleSortSearchConfig();

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery())
            ->addSort(
                [
                    PageIndexMap::STRING_SORT . '.foo' => [
                        'order' => SortConfigBuilder::DIRECTION_ASC,
                        'mode' => 'min',
                    ],
                ]
            );

        $requestParameters = [
            SortConfigBuilder::DEFAULT_SORT_PARAM_KEY => 'foo-param',
        ];

        return [$searchConfig, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForInvalidParameterSort()
    {
        $searchConfig = $this->createSimpleSortSearchConfig();

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery());

        $requestParameters = [
            SortConfigBuilder::DEFAULT_SORT_PARAM_KEY => 'bar',
        ];

        return [$searchConfig, $expectedQuery, $requestParameters];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createSimpleSortSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getSortConfigBuilder()
            ->addSort(
                (new SortConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_SORT)
            );

        return $searchConfig;
    }
}
