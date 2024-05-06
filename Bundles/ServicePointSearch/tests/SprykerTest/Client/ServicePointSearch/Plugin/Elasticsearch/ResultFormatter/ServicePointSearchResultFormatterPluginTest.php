<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Plugin\Elasticsearch\ResultFormatter;

use Codeception\Test\Unit;
use Elastica\Query;
use Elastica\Result;
use Elastica\ResultSet;
use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\ServicePointSearchTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;
use Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\ResultFormatter\ServicePointSearchResultFormatterPlugin;
use SprykerTest\Client\ServicePointSearch\ServicePointSearchClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointSearch
 * @group Plugin
 * @group Elasticsearch
 * @group ResultFormatter
 * @group ServicePointSearchResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class ServicePointSearchResultFormatterPluginTest extends Unit
{
    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\ResultFormatter\ServicePointSearchResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const NAME = 'ServicePointSearchCollection';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\ResultFormatter\ServicePointSearchResultFormatterPlugin::ITEMS_PER_PAGE
     *
     * @var string
     */
    protected const ITEMS_PER_PAGE = 'from';

    /**
     * @var string
     */
    protected const TEST_KEY = 'TEST_KEY';

    /**
     * @var string
     */
    protected const TEST_KEY_2 = 'TEST_KEY_2';

    /**
     * @var \SprykerTest\Client\ServicePointSearch\ServicePointSearchClientTester
     */
    protected ServicePointSearchClientTester $tester;

    /**
     * @return void
     */
    public function testGetNameShouldReturnCorrectName(): void
    {
        // Act
        $name = $this->createServicePointSearchResultFormatterPlugin()->getName();

        // Assert
        $this->assertSame(static::NAME, $name);
    }

    /**
     * @return void
     */
    public function testFormatResultShouldNotSetItemsPerPageWhenQueryDoesNotHaveParameter(): void
    {
        // Arrange
        $searchResult = $this->createResultSetMock($this->tester->createSearchQuery());

        // Act
        /** @var \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer $servicePointSearchCollectionTransfer */
        $servicePointSearchCollectionTransfer = $this->createServicePointSearchResultFormatterPlugin()->formatResult($searchResult);

        // Assert
        $this->assertNull($servicePointSearchCollectionTransfer->getItemsPerPage());
    }

    /**
     * @return void
     */
    public function testFormatResultShouldSetItemsPerPageWhenQueryHaveParameter(): void
    {
        // Arrange
        $searchQuery = $this->tester->createSearchQuery();
        $searchQuery->setParam(static::ITEMS_PER_PAGE, 3);

        $searchResult = $this->createResultSetMock($searchQuery);

        // Act
        /** @var \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer $servicePointSearchCollectionTransfer */
        $servicePointSearchCollectionTransfer = $this->createServicePointSearchResultFormatterPlugin()->formatResult($searchResult);

        // Assert
        $this->assertSame(3, $servicePointSearchCollectionTransfer->getItemsPerPage());
    }

    /**
     * @return void
     */
    public function testFormatResultShouldMapResultsToServicePointSearchTransfers(): void
    {
        // Arrange
        $results = [
            $this->createResultMock([
                ServicePointSearchTransfer::KEY => static::TEST_KEY,
            ]),
            $this->createResultMock([
                ServicePointSearchTransfer::KEY => static::TEST_KEY_2,
            ]),
        ];

        $searchResult = $this->createResultSetMock(
            $this->tester->createSearchQuery(),
            $results,
        );

        // Act
        /** @var \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer $servicePointSearchCollectionTransfer */
        $servicePointSearchCollectionTransfer = $this->createServicePointSearchResultFormatterPlugin()->formatResult($searchResult);

        // Assert
        $this->assertSame(2, $servicePointSearchCollectionTransfer->getNbResults());
        $this->assertSame(static::TEST_KEY, $servicePointSearchCollectionTransfer->getServicePoints()->offsetGet(0)->getKey());
        $this->assertSame(static::TEST_KEY_2, $servicePointSearchCollectionTransfer->getServicePoints()->offsetGet(1)->getKey());
    }

    /**
     * @param array<string, mixed> $searchResultData
     *
     * @return \Elastica\Result|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createResultMock(array $searchResultData): Result
    {
        $resultMock = $this->getMockBuilder(Result::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resultMock->method('getSource')->willReturn([
            ServicePointIndexMap::SEARCH_RESULT_DATA => $searchResultData,
        ]);

        return $resultMock;
    }

    /**
     * @param \Elastica\Query $query
     * @param list<\Elastica\Result> $results
     *
     * @return \Elastica\ResultSet|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createResultSetMock(Query $query, array $results = []): ResultSet
    {
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resultSetMock->method('getQuery')->willReturn($query);
        $resultSetMock->method('getResults')->willReturn($results);
        $resultSetMock->method('getTotalHits')->willReturn(count($results));

        return $resultSetMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin
     */
    protected function createServicePointSearchResultFormatterPlugin(): AbstractElasticsearchResultFormatterPlugin
    {
        return new ServicePointSearchResultFormatterPlugin();
    }
}
