<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CategoryStorage;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryNodeStorageBuilder;
use Generated\Shared\Transfer\CategorySearchResultTransfer;
use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;
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
    /**
     * @var int
     */
    protected const FIRST_CATEGORY_NODE_ID = 1;

    /**
     * @var string
     */
    protected const FIRST_CATEGORY_NAME = 'Category_1';

    /**
     * @var int
     */
    protected const FIRST_CATEGORY_DOC_COUNT = 224;

    /**
     * @var int
     */
    protected const SECOND_CATEGORY_DOC_COUNT = 33;

    /**
     * @var int
     */
    protected const SECOND_CATEGORY_NODE_ID = 2;

    /**
     * @var string
     */
    protected const SECOND_CATEGORY_NAME = 'Category_2';

    /**
     * @var int
     */
    protected const THIRD_CATEGORY_DOC_COUNT = 41;

    /**
     * @var int
     */
    protected const THIRD_CATEGORY_NODE_ID = 3;

    /**
     * @var string
     */
    protected const THIRD_CATEGORY_NAME = 'Category_3';

    /**
     * @var int
     */
    protected const FOURTH_CATEGORY_NODE_ID = 4;

    /**
     * @var string
     */
    protected const FOURTH_CATEGORY_NAME = 'Category_4';

    /**
     * @var int
     */
    protected const FIFTH_CATEGORY_NODE_ID = 5;

    /**
     * @var string
     */
    protected const FIFTH_CATEGORY_NAME = 'Category_5';

    /**
     * @var int
     */
    protected const SIXTH_CATEGORY_NODE_ID = 6;

    /**
     * @var string
     */
    protected const SIXTH_CATEGORY_NAME = 'Category_6';

    /**
     * @var int
     */
    protected const SEVENTH_CATEGORY_NODE_ID = 7;

    /**
     * @var string
     */
    protected const SEVENTH_CATEGORY_NAME = 'Category_7';

    /**
     * @var string
     */
    protected const TEST_LOCALE_NAME = 'en_US';

    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @uses \Spryker\Client\CategoryStorage\Plugin\Catalog\ResultFormatter\CategorySuggestionsSearchHttpResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const FORMATTER_NAME_CATEGORY = 'category';

    /**
     * @var \SprykerTest\Client\CategoryStorage\CategoryStorageClientTester
     */
    protected CategoryStorageClientTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterFormatsCategoryTree(): void
    {
        // Arrange
        $docCountAggregation = $this->getAggregationResult();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatCategoryTreeFilter($docCountAggregation, static::TEST_LOCALE_NAME, static::TEST_STORE_NAME);

        // Assert
        /** @var \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer $categoryNodeSearchResultTransfer */
        $categoryNodeSearchResultTransfer = $categoryNodeSearchResultTransfers->offsetGet(0);

        $this->assertCount(2, $categoryNodeSearchResultTransfers);

        $this->assertSame(
            static::FIRST_CATEGORY_NODE_ID,
            $categoryNodeSearchResultTransfer->getNodeId(),
            'The `nodeId` should be mapped correctly.',
        );
        $this->assertSame(
            static::FIRST_CATEGORY_DOC_COUNT,
            $categoryNodeSearchResultTransfer->getDocCount(),
            '`docCount` should be the same, taken from original buckets.',
        );
        $this->assertSame(
            static::SECOND_CATEGORY_DOC_COUNT,
            $categoryNodeSearchResultTransfer->getChildren()->offsetGet(0)->getDocCount(),
            '`docCount` should be the added for children objects too.',
        );
    }

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterTryToFormatTreeWhenCategoryDocCountsAreEmpty(): void
    {
        // Arrange
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatCategoryTreeFilter([], static::TEST_LOCALE_NAME, static::TEST_STORE_NAME);

        // Assert
        $this->assertSame(
            0,
            $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount(),
            'Fallback for absent `docCount` should be zero.',
        );
    }

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterTryToFormatTreeWhenCategoryNodeStoragesAreEmpty(): void
    {
        // Arrange
        $docCountAggregation = $this->getAggregationResult();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock(true));

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatCategoryTreeFilter($docCountAggregation, static::TEST_LOCALE_NAME, static::TEST_STORE_NAME);

        // Assert
        $this->assertEmpty($categoryNodeSearchResultTransfers, 'Expects empty collection in case empty category storage data.');
    }

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterTryToFormatTreeWithEmptyBucketsKey(): void
    {
        // Arrange
        $docCountAggregation = ['buckets' => []];

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatCategoryTreeFilter($docCountAggregation, static::TEST_LOCALE_NAME, static::TEST_STORE_NAME);

        // Assert
        $this->assertSame(
            0,
            $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount(),
            'For empty `docCount` buckets - expects fallback zero.',
        );
    }

    /**
     * @return void
     */
    public function testFormatCategoryTreeFilterTryToFormatTreeWithoutMandatoryKeyOrDocCount(): void
    {
        // Arrange
        $docCountAggregation = [
            'buckets' => [
                ['key1' => static::FIRST_CATEGORY_NODE_ID, 'doc_count1' => static::FIRST_CATEGORY_DOC_COUNT],
            ],
        ];

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatCategoryTreeFilter($docCountAggregation, static::TEST_LOCALE_NAME, static::TEST_STORE_NAME);

        // Assert
        $this->assertSame(
            0,
            $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount(),
            'For invalid keys and values in buckets - expects fallback zero.',
        );
    }

    /**
     * @return void
     */
    public function testFormatSearchHttpCategoryTreeFilterFormatsSearchHttpCategoryTree(): void
    {
        // Arrange
        $searchResult = $this->getSearchHttpResults();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatSearchHttpCategoryTree($searchResult);

        // Assert
        $this->assertSame(
            (static::FIRST_CATEGORY_DOC_COUNT + static::SECOND_CATEGORY_DOC_COUNT),
            $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount(),
        );
        $this->assertSame(
            static::SECOND_CATEGORY_DOC_COUNT,
            $categoryNodeSearchResultTransfers->offsetGet(0)->getChildren()->offsetGet(0)->getDocCount(),
        );
        $this->assertSame(
            static::THIRD_CATEGORY_DOC_COUNT,
            $categoryNodeSearchResultTransfers->offsetGet(1)->getDocCount(),
        );
    }

    /**
     * @return void
     */
    public function testFormatSearchHttpCategoryTreeFilterTryToFormatTreeWhenSearchResultsHasNoAggregationByCategory(): void
    {
        // Arrange
        $searchResult = $this->getEmptySearchHttpResults();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatSearchHttpCategoryTree($searchResult);

        // Assert
        $this->assertSame(
            0,
            $categoryNodeSearchResultTransfers->offsetGet(0)->getDocCount(),
        );
        $this->assertSame(
            0,
            $categoryNodeSearchResultTransfers->offsetGet(0)->getChildren()->offsetGet(0)->getDocCount(),
        );
        $this->assertSame(
            0,
            $categoryNodeSearchResultTransfers->offsetGet(1)->getDocCount(),
        );
    }

    /**
     * @return void
     */
    public function testFormatSearchHttpCategoryTreeFilterTryToFormatTreeWhenCategoryNodeStoragesAreEmpty(): void
    {
        // Arrange
        $searchResult = $this->getSearchHttpResults();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock(true));

        // Act
        $categoryNodeSearchResultTransfers = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatSearchHttpCategoryTree($searchResult);

        // Assert
        $this->assertEmpty($categoryNodeSearchResultTransfers, 'Expects empty collection in case empty category storage data.');
    }

    /**
     * @dataProvider getSuggestionsSearchHttpResultsDataProvider
     *
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer
     *
     * @return void
     */
    public function testFormatSuggestionsSearchHttpCategory(SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer): void
    {
        // Arrange
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMockWithDeepCategoryNodes());

        // Act
        $categoryNodeSearchResultData = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatSuggestionsSearchHttpCategory($suggestionsSearchHttpResponseTransfer);

        // Assert
        $names = array_column($categoryNodeSearchResultData, CategorySearchResultTransfer::NAME);

        foreach ($suggestionsSearchHttpResponseTransfer->getCategories() as $categoryName) {
            $this->assertContains(
                $categoryName,
                $names,
            );
        }
        foreach ($categoryNodeSearchResultData as $categoryNodeSearchResultDatum) {
            $this->assertSame(
                static::FORMATTER_NAME_CATEGORY,
                $categoryNodeSearchResultDatum[CategorySearchResultTransfer::TYPE],
            );
            $this->assertNotEmpty($categoryNodeSearchResultDatum[CategorySearchResultTransfer::URL]);
            $this->assertSame(static::FORMATTER_NAME_CATEGORY, $categoryNodeSearchResultDatum[CategorySearchResultTransfer::TYPE]);
        }
    }

    /**
     * @return void
     */
    public function testFormatEmptySuggestionsSearchHttpCategory(): void
    {
        // Arrange
        $suggestionsSearchHttpResponseTransfer = $this->getEmptySuggestionsSearchHttpResults();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock */
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getConfig'])
            ->getMock();

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock());

        // Act
        $categoryNodeSearchResultData = $this->tester
            ->getClientMock($categoryStorageFactoryMock)
            ->formatSuggestionsSearchHttpCategory($suggestionsSearchHttpResponseTransfer);

        // Assert
        $this->assertEmpty($categoryNodeSearchResultData);
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
                ->setName(static::FIRST_CATEGORY_NAME)
                ->setChildren(new ArrayObject([
                    (new CategoryNodeStorageBuilder())->build()
                        ->setNodeId(static::SECOND_CATEGORY_NODE_ID)
                        ->setName(static::SECOND_CATEGORY_NAME)
                        ->toArray(),
                    (new CategoryNodeStorageBuilder())->build(),
                    (new CategoryNodeStorageBuilder())->build(),
                ]))->toArray(),
            (new CategoryNodeStorageBuilder())
                ->build()
                ->setNodeId(static::THIRD_CATEGORY_NODE_ID)
                ->setName(static::THIRD_CATEGORY_NAME)
                ->toArray(),
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
     * @param bool $isEmpty
     *
     * @return \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface
     */
    protected function getStorageClientMockWithDeepCategoryNodes(bool $isEmpty = false): CategoryStorageToStorageInterface
    {
        $categoryNodeStorageTransfers = [
            (new CategoryNodeStorageBuilder())->build()
                ->setNodeId(static::FIRST_CATEGORY_NODE_ID)
                ->setName(static::FIRST_CATEGORY_NAME)
                ->setChildren(new ArrayObject([
                    (new CategoryNodeStorageBuilder())->build()
                        ->setNodeId(static::SECOND_CATEGORY_NODE_ID)
                        ->setName(static::SECOND_CATEGORY_NAME)
                        ->setChildren(new ArrayObject([
                            (new CategoryNodeStorageBuilder())
                                ->build()
                                ->setNodeId(static::THIRD_CATEGORY_NODE_ID)
                                ->setName(static::THIRD_CATEGORY_NAME)
                                ->toArray(),
                        ]))->toArray(),
                    (new CategoryNodeStorageBuilder())
                        ->build()
                        ->setNodeId(static::FOURTH_CATEGORY_NODE_ID)
                        ->setName(static::FOURTH_CATEGORY_NAME)
                        ->toArray(),
                    (new CategoryNodeStorageBuilder())->build(),
                ]))->toArray(),
            (new CategoryNodeStorageBuilder())
                ->build()
                ->setNodeId(static::FIFTH_CATEGORY_NODE_ID)
                ->setName(static::FIFTH_CATEGORY_NAME)
                ->toArray(),
            (new CategoryNodeStorageBuilder())->build()
                ->setNodeId(static::SIXTH_CATEGORY_NODE_ID)
                ->setName(static::SIXTH_CATEGORY_NAME)
                ->setChildren(new ArrayObject([
                    (new CategoryNodeStorageBuilder())->build()
                        ->setNodeId(static::SEVENTH_CATEGORY_NODE_ID)
                        ->setName(static::SEVENTH_CATEGORY_NAME)
                        ->toArray(),
                    (new CategoryNodeStorageBuilder())->build(),
                    (new CategoryNodeStorageBuilder())->build(),
                ]))->toArray(),
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
            'buckets' => [
                ['key' => static::FIRST_CATEGORY_NODE_ID, 'doc_count' => static::FIRST_CATEGORY_DOC_COUNT],
                ['key' => static::SECOND_CATEGORY_NODE_ID, 'doc_count' => static::SECOND_CATEGORY_DOC_COUNT],
                ['key' => static::THIRD_CATEGORY_NODE_ID, 'doc_count' => static::THIRD_CATEGORY_DOC_COUNT],
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\SearchHttpResponseTransfer
     */
    protected function getSearchHttpResults(): SearchHttpResponseTransfer
    {
        return (new SearchHttpResponseTransfer())
            ->setFacets(
                [
                    'category' => [
                        static::FIRST_CATEGORY_NAME => static::FIRST_CATEGORY_DOC_COUNT,
                        static::SECOND_CATEGORY_NAME => static::SECOND_CATEGORY_DOC_COUNT,
                        static::THIRD_CATEGORY_NAME => static::THIRD_CATEGORY_DOC_COUNT,
                    ],
                ],
            );
    }

    /**
     * @return \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer
     */
    protected function getSuggestionsSearchHttpResults(): SuggestionsSearchHttpResponseTransfer
    {
        return (new SuggestionsSearchHttpResponseTransfer())
            ->addCategory(static::SECOND_CATEGORY_NAME)
            ->addCategory(static::THIRD_CATEGORY_NAME);
    }

    /**
     * @return array<int, array<int, \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer>>
     */
    public function getSuggestionsSearchHttpResultsDataProvider(): array
    {
        return [
            [
                (new SuggestionsSearchHttpResponseTransfer())
                    ->addCategory(static::FIRST_CATEGORY_NAME)
                    ->addCategory(static::SECOND_CATEGORY_NAME)
                    ->addCategory(static::THIRD_CATEGORY_NAME)
                    ->addCategory(static::FOURTH_CATEGORY_NAME)
                    ->addCategory(static::FIFTH_CATEGORY_NAME)
                    ->addCategory(static::SIXTH_CATEGORY_NAME)
                    ->addCategory(static::SEVENTH_CATEGORY_NAME),
            ],
            [
                (new SuggestionsSearchHttpResponseTransfer())
                    ->addCategory(static::SECOND_CATEGORY_NAME)
                    ->addCategory(static::THIRD_CATEGORY_NAME),
            ],
            [
                (new SuggestionsSearchHttpResponseTransfer())
                    ->addCategory(static::SECOND_CATEGORY_NAME)
                    ->addCategory(static::FOURTH_CATEGORY_NAME),
            ],
            [
                (new SuggestionsSearchHttpResponseTransfer())
                    ->addCategory(static::FIFTH_CATEGORY_NAME)
                    ->addCategory(static::SEVENTH_CATEGORY_NAME),
            ],
            [
                (new SuggestionsSearchHttpResponseTransfer())
                    ->addCategory(static::THIRD_CATEGORY_NAME)
                    ->addCategory(static::FOURTH_CATEGORY_NAME)
                    ->addCategory(static::SEVENTH_CATEGORY_NAME),
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer
     */
    protected function getEmptySuggestionsSearchHttpResults(): SuggestionsSearchHttpResponseTransfer
    {
        return (new SuggestionsSearchHttpResponseTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\SearchHttpResponseTransfer
     */
    protected function getEmptySearchHttpResults(): SearchHttpResponseTransfer
    {
        return (new SearchHttpResponseTransfer())->setFacets([]);
    }
}
