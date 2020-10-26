<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CategoryStorage;

use ArrayObject;
use Codeception\Test\Unit;
use Elastica\ResultSet;
use Generated\Shared\DataBuilder\CategoryNodeStorageBuilder;
use Spryker\Client\CategoryStorage\CategoryStorageFactory;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageBridge;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CategoryStorage
 * @group FormatResultSetToCategoryTreeFilterTest
 * Add your own group annotations below this line
 */
class FormatResultSetToCategoryTreeFilterTest extends Unit
{
    protected const FIRST_CATEGORY_NODE_ID = 1;
    protected const FIRST_CATEGORY_DOT_COUNT = 224;

    protected const SECOND_CATEGORY_DOT_COUNT = 33;
    protected const SECOND_CATEGORY_NODE_ID = 2;

    protected const THIRD_CATEGORY_DOT_COUNT = 41;
    protected const THIRD_CATEGORY_NODE_ID = 3;

    /**
     * @var \SprykerTest\Client\CategoryStorage\CategoryStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFormatResultSetToCategoryTreeFilterFormatsCategoryTree(): void
    {
        // Arrange
        $searchResultMock = $this->getResultSetMock($this->getAggregationResult());

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorage', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorage')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatResultSetToCategoryTreeFilter($searchResultMock);

        // Assert
        /** @var \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer $categoryNodeSearchResultTransfer */
        $categoryNodeSearchResultTransfer = $categoryNodeSearchResultTransfers->offsetGet(0);

        $this->assertCount(2, $categoryNodeSearchResultTransfers);

        $this->assertSame(static::FIRST_CATEGORY_NODE_ID, $categoryNodeSearchResultTransfer->getNodeId());
        $this->assertSame(static::FIRST_CATEGORY_DOT_COUNT, $categoryNodeSearchResultTransfer->getDocCount());
        $this->assertSame(
            static::SECOND_CATEGORY_DOT_COUNT,
            $categoryNodeSearchResultTransfer->getChildren()->offsetGet(0)->getDocCount()
        );
    }

    /**
     * @return void
     */
    public function testFormatResultSetToCategoryTreeFilterTryToFormatTreeWhenCategoryDocCountsAreEmpty(): void
    {
        // Arrange
        $searchResultMock = $this->getResultSetMock($this->getAggregationResult(), true);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorage', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorage')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatResultSetToCategoryTreeFilter($searchResultMock);

        // Assert
        $this->assertSame(0, $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount());
    }

    /**
     * @return void
     */
    public function testFormatResultSetToCategoryTreeFilterTryToFormatTreeWhenCategoryNodeStoragesAreEmpty(): void
    {
        // Arrange
        $searchResultMock = $this->getResultSetMock($this->getAggregationResult());

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorage', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorage')
            ->willReturn($this->getStorageClientMock(true));

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatResultSetToCategoryTreeFilter($searchResultMock);

        // Assert
        $this->assertEmpty($categoryNodeSearchResultTransfers);
    }

    /**
     * @return void
     */
    public function testFormatResultSetToCategoryTreeFilterTryToFormatTreeWithoutMandatoryAggregationKey(): void
    {
        // Arrange
        $searchResultMock = $this->getResultSetMock([]);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorage', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorage')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatResultSetToCategoryTreeFilter($searchResultMock);

        // Assert
        $this->assertSame(0, $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount());
    }

    /**
     * @return void
     */
    public function testFormatResultSetToCategoryTreeFilterTryToFormatTreeWithoutMandatoryBucketsKey(): void
    {
        // Arrange
        $searchResultMock = $this->getResultSetMock(['category.all-parents.category' => []]);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorage', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorage')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatResultSetToCategoryTreeFilter($searchResultMock);

        // Assert
        $this->assertSame(0, $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount());
    }

    /**
     * @return void
     */
    public function testFormatResultSetToCategoryTreeFilterTryToFormatTreeWithoutMandatoryKeyOrDocCount(): void
    {
        // Arrange
        $searchResultMock = $this->getResultSetMock([
            'category.all-parents.category' => [
                'buckets' => [
                    ['key1' => static::FIRST_CATEGORY_NODE_ID, 'doc_count1' => static::FIRST_CATEGORY_DOT_COUNT],
                ],
            ],
        ]);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorage', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorage')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatResultSetToCategoryTreeFilter($searchResultMock);

        // Assert
        $this->assertSame(0, $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount());
    }

    /**
     * @param array $aggregationResult
     * @param bool $isEmpty
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\ResultSet
     */
    protected function getResultSetMock(array $aggregationResult, bool $isEmpty = false): ResultSet
    {
        $searchResultMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $searchResultMock
            ->method('getAggregations')
            ->willReturn($isEmpty ? [] : $aggregationResult);

        return $searchResultMock;
    }

    /**
     * @param bool $isEmpty
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface
     */
    protected function getStorageClientMock(bool $isEmpty = false): CategoryStorageToStorageInterface
    {
        $categoryNodeStorageTransfers = [
            (new CategoryNodeStorageBuilder())->build()
                ->setNodeId(static::FIRST_CATEGORY_NODE_ID)
                ->setChildren(new ArrayObject([
                    (new CategoryNodeStorageBuilder())->build()->setNodeId(static::SECOND_CATEGORY_NODE_ID)->toArray(),
                    (new CategoryNodeStorageBuilder())->build(),
                    (new CategoryNodeStorageBuilder())->build(),
                ]))->toArray(),
            (new CategoryNodeStorageBuilder())->build()->setNodeId(static::THIRD_CATEGORY_NODE_ID)->toArray(),
        ];

        $storageClientMock = $this->getMockBuilder(CategoryStorageToStorageBridge::class)
            ->onlyMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $storageClientMock
            ->method('get')
            ->willReturn(['category_nodes_storage' => $isEmpty ? [] : $categoryNodeStorageTransfers]);

        return $storageClientMock;
    }

    /**
     * @return array
     */
    protected function getAggregationResult(): array
    {
        return [
            'category.all-parents.category' => [
                'buckets' => [
                    ['key' => static::FIRST_CATEGORY_NODE_ID, 'doc_count' => static::FIRST_CATEGORY_DOT_COUNT],
                    ['key' => static::SECOND_CATEGORY_NODE_ID, 'doc_count' => static::SECOND_CATEGORY_DOT_COUNT],
                    ['key' => static::THIRD_CATEGORY_NODE_ID, 'doc_count' => static::THIRD_CATEGORY_DOT_COUNT],
                ],
            ],
        ];
    }
}
