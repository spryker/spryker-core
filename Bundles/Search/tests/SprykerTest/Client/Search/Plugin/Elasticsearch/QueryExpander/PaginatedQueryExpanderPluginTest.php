<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Transfer\PaginationConfigTransfer;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\PaginatedQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group PaginatedQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class PaginatedQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @dataProvider paginatedQueryExpanderDataProvider
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     * @param \Elastica\Query $expectedQuery
     * @param array $params
     *
     * @return void
     */
    public function testPaginatedQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(SearchConfigInterface $searchConfig, Query $expectedQuery, array $params = [])
    {
        $searchFactoryMock = $this->createSearchFactoryMockedWithSearchConfig($searchConfig);

        $queryExpander = new PaginatedQueryExpanderPlugin();
        $queryExpander->setFactory($searchFactoryMock);

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin(), $params);

        $query = $query->getSearchQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function paginatedQueryExpanderDataProvider()
    {
        return [
            'first page should be queried if there\'s no any request parameter' => $this->getDataForFirstPageWithEmptyParameters(),
            'query first page explicitly' => $this->getDataForExplicitFirstPage(),
            'query with non-default item count' => $this->getDataForNonDefaultItemCount(),
            'query with non-default invalid item count' => $this->getDataForNonDefaultInvalidItemCount(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForFirstPageWithEmptyParameters()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getPaginationConfigBuilder()
            ->setPagination(
                (new PaginationConfigTransfer())
                    ->setParameterName('page')
                    ->setItemsPerPageParameterName('ipp')
                    ->setDefaultItemsPerPage(10)
            );

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery())
            ->setFrom(0)
            ->setSize(10);

        $requestParameters = [];

        return [$searchConfig, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForExplicitFirstPage()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getPaginationConfigBuilder()
            ->setPagination(
                (new PaginationConfigTransfer())
                    ->setParameterName('page')
                    ->setItemsPerPageParameterName('ipp')
                    ->setDefaultItemsPerPage(10)
            );

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery())
            ->setFrom(0)
            ->setSize(10);

        $requestParameters = [
            'page' => 1,
        ];

        return [$searchConfig, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForNonDefaultItemCount()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getPaginationConfigBuilder()
            ->setPagination(
                (new PaginationConfigTransfer())
                    ->setParameterName('page')
                    ->setItemsPerPageParameterName('ipp')
                    ->setDefaultItemsPerPage(10)
                    ->setValidItemsPerPageOptions([10, 20, 30])
            );

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery())
            ->setFrom(0)
            ->setSize(20);

        $requestParameters = [
            'ipp' => 20,
        ];

        return [$searchConfig, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForNonDefaultInvalidItemCount()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getPaginationConfigBuilder()
            ->setPagination(
                (new PaginationConfigTransfer())
                    ->setParameterName('page')
                    ->setItemsPerPageParameterName('ipp')
                    ->setDefaultItemsPerPage(10)
                    ->setValidItemsPerPageOptions([10, 20, 30])
            );

        $expectedQuery = (new Query())
            ->setQuery(new BoolQuery())
            ->setFrom(0)
            ->setSize(10);

        $requestParameters = [
            'ipp' => 15,
        ];

        return [$searchConfig, $expectedQuery, $requestParameters];
    }
}
