<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Transfer\PaginationConfigTransfer;
use Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\PaginatedQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group QueryExpander
 * @group PaginatedQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class PaginatedQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @dataProvider paginatedQueryExpanderDataProvider
     *
     * @param \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface $searchConfigMock
     * @param \Elastica\Query $expectedQuery
     * @param array $params
     *
     * @return void
     */
    public function testPaginatedQueryExpanderShouldExpandTheBaseQueryAccordingToRequestParameters(
        SearchConfigInterface $searchConfigMock,
        Query $expectedQuery,
        array $params = []
    ): void {
        // Arrange
        $searchFactoryMock = $this->createSearchFactoryMockedWithSearchConfig($searchConfigMock);

        $queryExpander = new PaginatedQueryExpanderPlugin();
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
    public function paginatedQueryExpanderDataProvider(): array
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
    protected function getDataForFirstPageWithEmptyParameters(): array
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getPaginationConfig()
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

        return [$searchConfigMock, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForExplicitFirstPage(): array
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getPaginationConfig()
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

        return [$searchConfigMock, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForNonDefaultItemCount(): array
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getPaginationConfig()
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

        return [$searchConfigMock, $expectedQuery, $requestParameters];
    }

    /**
     * @return array
     */
    protected function getDataForNonDefaultInvalidItemCount(): array
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getPaginationConfig()
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

        return [$searchConfigMock, $expectedQuery, $requestParameters];
    }
}
