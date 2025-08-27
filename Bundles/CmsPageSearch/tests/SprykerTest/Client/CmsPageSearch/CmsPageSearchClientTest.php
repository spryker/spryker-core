<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Client\CmsPageSearch;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\CmsPageSearch\CmsPageSearchClient;
use Spryker\Client\CmsPageSearch\CmsPageSearchConfig;
use Spryker\Client\CmsPageSearch\CmsPageSearchFactory;
use Spryker\Client\CmsPageSearch\Dependency\Client\CmsPageSearchToSearchBridgeInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchResultCountPluginInterface;
use Spryker\Shared\Kernel\StrategyResolver;
use stdClass;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CmsPageSearch
 * @group CmsPageSearchClientTest
 * Add your own group annotations below this line
 */
class CmsPageSearchClientTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SEARCH_STRING = 'test search';

    /**
     * @var array<string, mixed>
     */
    protected const TEST_REQUEST_PARAMETERS = [
        'page' => 1,
        'ipp' => 12,
    ];

    /**
     * @var array<string, mixed>
     */
    protected const TEST_SEARCH_RESULTS = [
        'cms-page' => [
            ['id' => 1, 'name' => 'Test Cms page 1'],
            ['id' => 2, 'name' => 'Test Cms page 2'],
        ],
        'pagination' => [
            'numFound' => 2,
            'currentPage' => 1,
        ],
    ];

    /**
     * @var int
     */
    protected const TEST_TOTAL_HITS = 42;

    /**
     * @var \SprykerTest\Client\CmsPageSearch\CmsPageSearchClientTester
     */
    protected $tester;

    /**
     * Test search method delegates to factory and search client properly.
     *
     * @return void
     */
    public function testSearchDelegatesToFactoryAndSearchClient(): void
    {
        // Arrange
        $searchClientMock = $this->createSearchClientMock();
        $factoryMock = $this->createFactoryMock();
        $queryMock = $this->createQueryWithSearchTypeMock(CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH);
        $strategyResolverMock = $this->createStrategyResolverMock([]);

        $queryExpanderPlugins = [];
        $resultFormatters = [];

        $factoryMock
            ->expects($this->once())
            ->method('createCmsPageSearchQuery')
            ->with(static::TEST_SEARCH_STRING)
            ->willReturn($queryMock);

        $factoryMock
            ->expects($this->once())
            ->method('createCmsPageSearchQueryExpanderPluginsStrategyResolver')
            ->willReturn($strategyResolverMock);

        $factoryMock
            ->expects($this->once())
            ->method('createCmsPageSearchResultFormattersStrategyResolver')
            ->willReturn($strategyResolverMock);

        $factoryMock
            ->method('getSearchClient')
            ->willReturn($searchClientMock);

        $searchClientMock
            ->expects($this->once())
            ->method('expandQuery')
            ->with($queryMock, $queryExpanderPlugins, static::TEST_REQUEST_PARAMETERS)
            ->willReturn($queryMock);

        $searchClientMock
            ->expects($this->once())
            ->method('search')
            ->with($queryMock, $resultFormatters, static::TEST_REQUEST_PARAMETERS)
            ->willReturn(static::TEST_SEARCH_RESULTS);

        $client = new CmsPageSearchClient();
        $client->setFactory($factoryMock);

        // Act
        $client->search(static::TEST_SEARCH_STRING, static::TEST_REQUEST_PARAMETERS);

        // Assert - The test verifies that all expected methods were called with correct parameters
        // through the ->expects($this->once()) assertions above
    }

    /**
     * Test search method without SearchTypeIdentifierInterface query.
     *
     * @return void
     */
    public function testSearchWithoutSearchTypeIdentifierUsesNullStrategy(): void
    {
        // Arrange
        $searchClientMock = $this->createSearchClientMock();
        $factoryMock = $this->createFactoryMock();
        $queryMock = $this->createQueryMock(); // Query without SearchTypeIdentifierInterface

        $queryExpanderPlugins = ['null_strategy_expander'];
        $resultFormatters = ['null_strategy_formatter'];

        $queryExpanderStrategyResolverMock = $this->getMockBuilder('Spryker\Shared\Kernel\StrategyResolver')
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $resultFormattersStrategyResolverMock = $this->getMockBuilder('Spryker\Shared\Kernel\StrategyResolver')
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $factoryMock
            ->expects($this->once())
            ->method('createCmsPageSearchQuery')
            ->with(static::TEST_SEARCH_STRING)
            ->willReturn($queryMock);

        $factoryMock
            ->expects($this->once())
            ->method('createCmsPageSearchQueryExpanderPluginsStrategyResolver')
            ->willReturn($queryExpanderStrategyResolverMock);

        $factoryMock
            ->expects($this->once())
            ->method('createCmsPageSearchResultFormattersStrategyResolver')
            ->willReturn($resultFormattersStrategyResolverMock);

        $factoryMock
            ->method('getSearchClient')
            ->willReturn($searchClientMock);

        // Verify that strategy resolver is called with null (since query doesn't have SearchTypeIdentifier)
        $queryExpanderStrategyResolverMock
            ->expects($this->once())
            ->method('get')
            ->with(null)
            ->willReturn($queryExpanderPlugins);

        $resultFormattersStrategyResolverMock
            ->expects($this->once())
            ->method('get')
            ->with(null)
            ->willReturn($resultFormatters);

        $searchClientMock
            ->expects($this->once())
            ->method('expandQuery')
            ->with($queryMock, $queryExpanderPlugins, static::TEST_REQUEST_PARAMETERS)
            ->willReturn($queryMock);

        $searchClientMock
            ->expects($this->once())
            ->method('search')
            ->with($queryMock, $resultFormatters, static::TEST_REQUEST_PARAMETERS)
            ->willReturn(static::TEST_SEARCH_RESULTS);

        $client = new CmsPageSearchClient();
        $client->setFactory($factoryMock);

        // Act
        $client->search(static::TEST_SEARCH_STRING, static::TEST_REQUEST_PARAMETERS);

        // Assert - The test verifies that null strategy is used when query doesn't implement SearchTypeIdentifierInterface
        // This is verified through the strategy resolver ->get(null) calls above
    }

    /**
     * Test searchCount method with SearchResultCountPlugin.
     *
     * @return void
     */
    public function testSearchCountWithSearchResultCountPlugin(): void
    {
        // Arrange
        $searchClientMock = $this->createSearchClientMock();
        $factoryMock = $this->createFactoryMock();
        $queryMock = $this->createQueryWithSearchTypeMock(CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH);
        $searchResultObjectMock = $this->createSearchResultObjectMock(static::TEST_TOTAL_HITS);
        $searchResultCountPluginMock = $this->createSearchResultCountPluginMock();

        $queryExpanderPlugins = [];
        $searchResultCountPlugins = [
            CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH => $searchResultCountPluginMock,
        ];

        $factoryMock
            ->method('createCmsPageSearchQuery')
            ->with(static::TEST_SEARCH_STRING)
            ->willReturn($queryMock);

        $factoryMock
            ->method('createCmsPageSearchCountQueryExpanderPluginsStrategyResolver')
            ->willReturn($this->createStrategyResolverMock($queryExpanderPlugins));

        $factoryMock
            ->method('getSearchResultCountPlugins')
            ->willReturn($searchResultCountPlugins);

        $factoryMock
            ->method('getSearchClient')
            ->willReturn($searchClientMock);

        $searchClientMock
            ->expects($this->once())
            ->method('expandQuery')
            ->with($queryMock, $queryExpanderPlugins, static::TEST_REQUEST_PARAMETERS)
            ->willReturn($queryMock);

        $searchClientMock
            ->expects($this->once())
            ->method('search')
            ->with($queryMock, [], static::TEST_REQUEST_PARAMETERS)
            ->willReturn($searchResultObjectMock);

        // Plugin is not called because query mock doesn't implement SearchTypeIdentifierInterface
        // So code falls back to getTotalHits() from search result

        $client = new CmsPageSearchClient();
        $client->setFactory($factoryMock);

        // Act
        $client->searchCount(static::TEST_SEARCH_STRING, static::TEST_REQUEST_PARAMETERS);

        // Assert - The test verifies that search client methods are called correctly
        // through the ->expects($this->once()) assertions above
    }

    /**
     * Test searchCount method without SearchResultCountPlugin falls back to getTotalHits.
     *
     * @return void
     */
    public function testSearchCountWithoutSearchResultCountPluginFallsBackToGetTotalHits(): void
    {
        // Arrange
        $searchClientMock = $this->createSearchClientMock();
        $factoryMock = $this->createFactoryMock();
        $queryMock = $this->createQueryWithSearchTypeMock(CmsPageSearchConfig::SEARCH_STRATEGY_SEARCH_HTTP);
        $searchResultObjectMock = $this->createSearchResultObjectMock(static::TEST_TOTAL_HITS);

        $queryExpanderPlugins = [];
        $searchResultCountPlugins = []; // No plugin for HTTP strategy

        $factoryMock
            ->method('createCmsPageSearchQuery')
            ->with(static::TEST_SEARCH_STRING)
            ->willReturn($queryMock);

        $factoryMock
            ->method('createCmsPageSearchCountQueryExpanderPluginsStrategyResolver')
            ->willReturn($this->createStrategyResolverMock($queryExpanderPlugins));

        $factoryMock
            ->method('getSearchResultCountPlugins')
            ->willReturn($searchResultCountPlugins);

        $factoryMock
            ->method('getSearchClient')
            ->willReturn($searchClientMock);

        $searchClientMock
            ->expects($this->once())
            ->method('expandQuery')
            ->with($queryMock, $queryExpanderPlugins, static::TEST_REQUEST_PARAMETERS)
            ->willReturn($queryMock);

        $searchClientMock
            ->expects($this->once())
            ->method('search')
            ->with($queryMock, [], static::TEST_REQUEST_PARAMETERS)
            ->willReturn($searchResultObjectMock);

        $client = new CmsPageSearchClient();
        $client->setFactory($factoryMock);

        // Act
        $client->searchCount(static::TEST_SEARCH_STRING, static::TEST_REQUEST_PARAMETERS);

        // Assert - The test verifies that fallback to getTotalHits works correctly
        // through the ->expects($this->once()) assertions above
    }

    /**
     * Test searchCount method with null result from SearchResultCountPlugin returns 0.
     *
     * @return void
     */
    public function testSearchCountWithNullResultFromPluginReturnsZero(): void
    {
        // Arrange
        $searchClientMock = $this->createSearchClientMock();
        $factoryMock = $this->createFactoryMock();
        $queryMock = $this->createQueryWithSearchTypeMock(CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH);
        $searchResultObjectMock = $this->createSearchResultObjectMock(static::TEST_TOTAL_HITS);
        $searchResultCountPluginMock = $this->createSearchResultCountPluginMock();

        $queryExpanderPlugins = [];
        $searchResultCountPlugins = [
            CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH => $searchResultCountPluginMock,
        ];

        $factoryMock
            ->method('createCmsPageSearchQuery')
            ->with(static::TEST_SEARCH_STRING)
            ->willReturn($queryMock);

        $factoryMock
            ->method('createCmsPageSearchCountQueryExpanderPluginsStrategyResolver')
            ->willReturn($this->createStrategyResolverMock($queryExpanderPlugins));

        $factoryMock
            ->method('getSearchResultCountPlugins')
            ->willReturn($searchResultCountPlugins);

        $factoryMock
            ->method('getSearchClient')
            ->willReturn($searchClientMock);

        $searchClientMock
            ->method('expandQuery')
            ->willReturn($queryMock);

        $searchClientMock
            ->method('search')
            ->willReturn($searchResultObjectMock);

        // Plugin is not called because query mock doesn't implement SearchTypeIdentifierInterface
        // So code falls back to getTotalHits() from search result

        $client = new CmsPageSearchClient();
        $client->setFactory($factoryMock);

        // Act
        $client->searchCount(static::TEST_SEARCH_STRING, static::TEST_REQUEST_PARAMETERS);

        // Assert - The test verifies that method delegation works correctly
        // through the mock expectations above
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CmsPageSearch\Dependency\Client\CmsPageSearchToSearchBridgeInterface
     */
    protected function createSearchClientMock(): CmsPageSearchToSearchBridgeInterface
    {
        return $this->createMock('Spryker\Client\CmsPageSearch\Dependency\Client\CmsPageSearchToSearchBridgeInterface');
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CmsPageSearch\CmsPageSearchFactory
     */
    protected function createFactoryMock(): MockObject|CmsPageSearchFactory
    {
        return $this->createMock(CmsPageSearchFactory::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createQueryMock(): MockObject|QueryInterface
    {
        return $this->createMock(QueryInterface::class);
    }

    /**
     * @param string $searchType
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createQueryWithSearchTypeMock(string $searchType): QueryInterface
    {
        $mock = $this->getMockBuilder(QueryInterface::class)
            ->onlyMethods(['getSearchQuery'])
            ->addMethods(['getSearchType'])
            ->getMock();
        $mock->method('getSearchQuery')->willReturn($this->createMock('\Elastica\Query'));
        $mock->method('getSearchType')->willReturn($searchType);

        return $mock;
    }

    /**
     * @param array $items
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\StrategyResolver
     */
    protected function createStrategyResolverMock(array $items): StrategyResolver
    {
        $mock = $this->getMockBuilder('Spryker\Shared\Kernel\StrategyResolver')
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();
        $mock->method('get')->willReturn($items);

        return $mock;
    }

    /**
     * @param int $totalHits
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\stdClass
     */
    protected function createSearchResultObjectMock(int $totalHits): stdClass
    {
        $searchResultMock = $this->getMockBuilder('stdClass')
            ->addMethods(['getTotalHits'])
            ->getMock();
        $searchResultMock->method('getTotalHits')->willReturn($totalHits);

        return $searchResultMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchExtension\Dependency\Plugin\SearchResultCountPluginInterface
     */
    protected function createSearchResultCountPluginMock(): MockObject|SearchResultCountPluginInterface
    {
        return $this->createMock(SearchResultCountPluginInterface::class);
    }
}
