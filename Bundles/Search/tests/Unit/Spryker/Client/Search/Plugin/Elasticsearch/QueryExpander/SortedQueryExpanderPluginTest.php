<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Config\SortConfigBuilder;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SortedQueryExpanderPlugin;

/**
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group FacetQueryExpanderPlugin
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
    public function testPaginationExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(SearchConfigInterface $searchConfig, Query $expectedQuery, array $params = [])
    {
        $queryExpander = new SortedQueryExpanderPlugin();
        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin(), $searchConfig, $params);

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
            ->setQuery(new Query\BoolQuery());

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
            ->setQuery(new Query\BoolQuery())
            ->setSort(
                [
                    PageIndexMap::STRING_SORT . '.foo' => [
                        'order' => SortConfigBuilder::DIRECTION_ASC,
                        'mode' => 'min',
                    ],
                ]
            );

        $requestParameters = [
            SortConfigBuilder::DEFAULT_SORT_PARAM_KEY => 'foo',
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
            ->setQuery(new Query\BoolQuery());

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
                    ->setParameterName('foo')
                    ->setFieldName(PageIndexMap::STRING_SORT)
            );

        return $searchConfig;
    }

}
