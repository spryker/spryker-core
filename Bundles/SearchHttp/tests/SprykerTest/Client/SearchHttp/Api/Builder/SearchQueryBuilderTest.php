<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Client\SearchHttp\Api\Builder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchQueryTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use ReflectionClass;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilder;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Api
 * @group Builder
 * @group SearchQueryBuilderTest
 * Add your own group annotations below this line
 */
class SearchQueryBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const TEST_SOURCE_IDENTIFIER = 'cms_page';

    /**
     * @var string
     */
    protected const TEST_QUERY_STRING = 'test search';

    /**
     * @var string
     */
    protected const TEST_USER_TOKEN = 'user123';

    /**
     * @var \Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilder
     */
    protected SearchQueryBuilder $searchQueryBuilder;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface
     */
    protected $storeClientMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->storeClientMock = $this->createMock(SearchHttpToStoreClientInterface::class);
        $this->storeClientMock
            ->method('getCurrentStore')
            ->willReturn($this->createStoreTransferMock());

        $this->searchQueryBuilder = new SearchQueryBuilder($this->storeClientMock);
    }

    /**
     * Test that basic query building works without SearchContextAwareQueryInterface.
     *
     * @return void
     */
    public function testBuildDoesNotAddSourceIdentifierWhenQueryIsNotSearchContextAware(): void
    {
        // Arrange
        $searchQuery = $this->createBasicQueryMock();

        // Act
        $result = $this->searchQueryBuilder->build($searchQuery);

        // Assert
        $this->assertArrayNotHasKey('sourceIdentifier', $result);
        $this->assertArrayHasKey('store', $result);
        $this->assertEquals(static::TEST_STORE_NAME, $result['store']);
    }

    /**
     * Test that query building includes all basic fields.
     *
     * @return void
     */
    public function testBuildIncludesBasicRequiredFields(): void
    {
        // Arrange
        $searchQuery = $this->createBasicQueryMock();

        // Act
        $result = $this->searchQueryBuilder->build($searchQuery);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('store', $result);
        $this->assertEquals(static::TEST_STORE_NAME, $result['store']);
    }

    /**
     * Test that query string is properly added to the query.
     *
     * @return void
     */
    public function testBuildAddsQueryStringCorrectly(): void
    {
        // Arrange
        $searchQuery = $this->createBasicQueryMock();

        // Act
        $result = $this->searchQueryBuilder->build($searchQuery);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('query', $result);
        $this->assertEquals(static::TEST_QUERY_STRING, $result['query']);
    }

    /**
     * Test addSourceIdentifier method directly using reflection.
     *
     * @return void
     */
    public function testAddSourceIdentifierAddsCorrectSourceIdentifier(): void
    {
        // Arrange
        $searchContextTransfer = (new SearchContextTransfer())
            ->setSourceIdentifier(static::TEST_SOURCE_IDENTIFIER);

        $initialQuery = ['store' => static::TEST_STORE_NAME];

        // Use reflection to access the protected method
        $reflection = new ReflectionClass($this->searchQueryBuilder);
        $method = $reflection->getMethod('addSourceIdentifier');
        $method->setAccessible(true);

        // Act
        $result = $method->invoke($this->searchQueryBuilder, $initialQuery, $searchContextTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('sourceIdentifier', $result);
        $this->assertEquals(static::TEST_SOURCE_IDENTIFIER, $result['sourceIdentifier']);
        $this->assertArrayHasKey('store', $result); // Ensure original data is preserved
        $this->assertEquals(static::TEST_STORE_NAME, $result['store']);
    }

    /**
     * Test addSourceIdentifier with null source identifier.
     *
     * @return void
     */
    public function testAddSourceIdentifierWithNullSourceIdentifier(): void
    {
        // Arrange
        $searchContextTransfer = (new SearchContextTransfer())
            ->setSourceIdentifier(null);

        $initialQuery = ['store' => static::TEST_STORE_NAME];

        // Use reflection to access the protected method
        $reflection = new ReflectionClass($this->searchQueryBuilder);
        $method = $reflection->getMethod('addSourceIdentifier');
        $method->setAccessible(true);

        // Act
        $result = $method->invoke($this->searchQueryBuilder, $initialQuery, $searchContextTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('sourceIdentifier', $result);
        $this->assertNull($result['sourceIdentifier']);
        $this->assertArrayHasKey('store', $result); // Ensure original data is preserved
        $this->assertEquals(static::TEST_STORE_NAME, $result['store']);
    }

    /**
     * Test addSourceIdentifier with empty string source identifier.
     *
     * @return void
     */
    public function testAddSourceIdentifierWithEmptyStringSourceIdentifier(): void
    {
        // Arrange
        $searchContextTransfer = (new SearchContextTransfer())
            ->setSourceIdentifier('');

        $initialQuery = ['store' => static::TEST_STORE_NAME];

        // Use reflection to access the protected method
        $reflection = new ReflectionClass($this->searchQueryBuilder);
        $method = $reflection->getMethod('addSourceIdentifier');
        $method->setAccessible(true);

        // Act
        $result = $method->invoke($this->searchQueryBuilder, $initialQuery, $searchContextTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('sourceIdentifier', $result);
        $this->assertEquals('', $result['sourceIdentifier']);
        $this->assertArrayHasKey('store', $result); // Ensure original data is preserved
        $this->assertEquals(static::TEST_STORE_NAME, $result['store']);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createBasicQueryMock(): QueryInterface
    {
        $mock = $this->createMock(QueryInterface::class);

        $searchQueryTransfer = (new SearchQueryTransfer())
            ->setQueryString(static::TEST_QUERY_STRING)
            ->setUserToken(static::TEST_USER_TOKEN);

        $mock
            ->method('getSearchQuery')
            ->willReturn($searchQueryTransfer);

        return $mock;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createStoreTransferMock()
    {
        $storeTransfer = $this->getMockBuilder(StoreTransfer::class)
            ->onlyMethods(['getName'])
            ->getMock();

        $storeTransfer
            ->method('getName')
            ->willReturn(static::TEST_STORE_NAME);

        return $storeTransfer;
    }
}
