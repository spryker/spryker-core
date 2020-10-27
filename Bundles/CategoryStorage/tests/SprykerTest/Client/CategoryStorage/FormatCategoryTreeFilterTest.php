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
 * @group FormatCategoryTreeFilterTest
 * Add your own group annotations below this line
 */
class FormatCategoryTreeFilterTest extends Unit
{
    protected const FIRST_CATEGORY_NODE_ID = 1;
    protected const FIRST_CATEGORY_DOC_COUNT = 224;

    protected const SECOND_CATEGORY_DOC_COUNT = 33;
    protected const SECOND_CATEGORY_NODE_ID = 2;

    protected const THIRD_CATEGORY_DOC_COUNT = 41;
    protected const THIRD_CATEGORY_NODE_ID = 3;

    /**
     * @var \SprykerTest\Client\CategoryStorage\CategoryStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterFormatsCategoryTree(): void
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
            ->formatCategoryTreeFilter($searchResultMock);

        // Assert
        /** @var \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer $categoryNodeSearchResultTransfer */
        $categoryNodeSearchResultTransfer = $categoryNodeSearchResultTransfers->offsetGet(0);

        $this->assertCount(2, $categoryNodeSearchResultTransfers);

        $this->assertSame(
            static::FIRST_CATEGORY_NODE_ID,
            $categoryNodeSearchResultTransfer->getNodeId(),
            'The `nodeId` should be mapped correctly.'
        );
        $this->assertSame(
            static::FIRST_CATEGORY_DOC_COUNT,
            $categoryNodeSearchResultTransfer->getDocCount(),
            '`docCount` should be the same, taken from original buckets.'
        );
        $this->assertSame(
            static::SECOND_CATEGORY_DOC_COUNT,
            $categoryNodeSearchResultTransfer->getChildren()->offsetGet(0)->getDocCount(),
            '`docCount` should be the added for children objects too.'
        );
    }

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterTryToFormatTreeWhenCategoryDocCountsAreEmpty(): void
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
            ->formatCategoryTreeFilter($searchResultMock);

        // Assert
        $this->assertSame(
            0,
            $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount(),
            'Fallback for absent `docCount` should be zero.'
        );
    }

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterTryToFormatTreeWhenCategoryNodeStoragesAreEmpty(): void
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
            ->formatCategoryTreeFilter($searchResultMock);

        // Assert
        $this->assertEmpty($categoryNodeSearchResultTransfers, 'Expects empty collection in case empty category storage data.');
    }

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterTryToFormatTreeWithoutMandatoryBucketsKey(): void
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
            ->formatCategoryTreeFilter($searchResultMock);

        // Assert
        $this->assertSame(
            0,
            $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount(),
            'For empty `docCount` buckets - expects fallback zero.'
        );
    }

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterTryToFormatTreeWithoutMandatoryKeyOrDocCount(): void
    {
        // Arrange
        $searchResultMock = $this->getResultSetMock([
            'category.all-parents.category' => [
                'buckets' => [
                    ['key1' => static::FIRST_CATEGORY_NODE_ID, 'doc_count1' => static::FIRST_CATEGORY_DOC_COUNT],
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
            ->formatCategoryTreeFilter($searchResultMock);

        // Assert
        $this->assertSame(
            0,
            $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount(),
            'For invalid keys and values in buckets - expects fallback zero.'
        );
    }

    /**
     * @param array $aggregationResult
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\ResultSet
     */
    protected function getResultSetMock(array $aggregationResult): ResultSet
    {
        $searchResultMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $searchResultMock
            ->method('getAggregations')
            ->willReturn($aggregationResult);

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
                    ['key' => static::FIRST_CATEGORY_NODE_ID, 'doc_count' => static::FIRST_CATEGORY_DOC_COUNT],
                    ['key' => static::SECOND_CATEGORY_NODE_ID, 'doc_count' => static::SECOND_CATEGORY_DOC_COUNT],
                    ['key' => static::THIRD_CATEGORY_NODE_ID, 'doc_count' => static::THIRD_CATEGORY_DOC_COUNT],
                ],
            ],
        ];
    }
}
