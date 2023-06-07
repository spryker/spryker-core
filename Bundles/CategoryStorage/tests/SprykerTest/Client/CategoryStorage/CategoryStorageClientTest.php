<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CategoryStorage;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryNodeStorageBuilder;
use Generated\Shared\DataBuilder\ProductAbstractCategoryStorageBuilder;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\ProductCategoryStorageTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageFactory;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageBridge;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface;
use Spryker\Client\CategoryStorage\Storage\CategoryNodeStorage;
use Spryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CategoryStorage
 * @group CategoryStorageClientTest
 * Add your own group annotations below this line
 */
class CategoryStorageClientTest extends Unit
{
    /**
     * @var string
     */
    protected const CATEGORY_NAME_COMPUTER = 'computer';

    /**
     * @var string
     */
    protected const CATEGORY_URL_COMPUTER = '/en/computer';

    /**
     * @var int
     */
    protected const CATEGORY_ID_ROOT = 1;

    /**
     * @var int
     */
    protected const CATEGORY_ID_PARENT = 3;

    /**
     * @var int
     */
    protected const CATEGORY_NODE_ID_ROOT = 1;

    /**
     * @var int
     */
    protected const CATEGORY_NODE_ID_CHILDREN = 2;

    /**
     * @var int
     */
    protected const CATEGORY_NODE_ID_PARENT = 3;

    /**
     * @var int
     */
    protected const PRODUCT_ABSTRACT_ID = -1;

    /**
     * @var string
     */
    protected const TEST_KEY = 'TEST_KEY';

    /**
     * @var string
     */
    protected const TEST_KEY_2 = 'TEST_KEY_2';

    /**
     * @var string
     */
    protected const TEST_KEY_3 = 'TEST_KEY_3';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const STORE_DE = 'DE';

    /**
     * @var \SprykerTest\Client\CategoryStorage\CategoryStorageClientTester
     */
    protected CategoryStorageClientTester $tester;

    /**
     * @return void
     */
    public function testExpandProductCategoriesWithParentIdsWillNotExpandWhenParentIdsAreEmpty(): void
    {
        // Arrange
        $categoryStorageFactoryMock = $this->getCategoryStorageFactoryMock();
        $categoryStorageClientMock = $this->tester->getClientMock($categoryStorageFactoryMock);

        $productAbstractCategoryStorageTransfer = new ProductAbstractCategoryStorageTransfer();
        $productAbstractCategoryStorageCollectionTransfer = (new ProductAbstractCategoryStorageCollectionTransfer())
            ->addProductAbstractCategory($productAbstractCategoryStorageTransfer);

        // Act
        $productAbstractCategoryStorageCollectionTransfer = $categoryStorageClientMock->expandProductCategoriesWithParentIds($productAbstractCategoryStorageCollectionTransfer, 'en_US', 'US');

        // Assert
        $productAbstractCategoryStorageTransfers = $productAbstractCategoryStorageCollectionTransfer->getProductAbstractCategories()->getArrayCopy();

        $this->assertCount(1, $productAbstractCategoryStorageTransfers);

        /** @var \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransferNew */
        $productAbstractCategoryStorageTransferNew = array_pop($productAbstractCategoryStorageTransfers);
        $productCategoryStorageTransfers = $productAbstractCategoryStorageTransferNew->getCategories();

        $this->assertCount(0, $productCategoryStorageTransfers);
    }

    /**
     * @return void
     */
    public function testExpandProductCategoriesWithParentIdsThrowsExceptionWhenCategoryNodeIdIsMissing(): void
    {
        // Arrange
        $categoryStorageFactoryMock = $this->getCategoryStorageFactoryMock();

        $categoryStorageClientMock = $this->tester->getClientMock($categoryStorageFactoryMock);

        $productAbstractCategoryStorageTransfer = (new ProductAbstractCategoryStorageBuilder([
            ProductAbstractCategoryStorageTransfer::ID_PRODUCT_ABSTRACT => static::PRODUCT_ABSTRACT_ID,
            ProductAbstractCategoryStorageTransfer::CATEGORIES => [
                [
                    ProductCategoryStorageTransfer::CATEGORY_ID => static::CATEGORY_ID_ROOT,
                    ProductCategoryStorageTransfer::CATEGORY_NODE_ID => null,
                    ProductCategoryStorageTransfer::NAME => static::CATEGORY_NAME_COMPUTER,
                    ProductCategoryStorageTransfer::URL => static::CATEGORY_URL_COMPUTER,
                    ProductCategoryStorageTransfer::PARENT_CATEGORY_IDS => [],
                ],
            ],
        ]))->build();
        $productAbstractCategoryStorageCollectionTransfer = (new ProductAbstractCategoryStorageCollectionTransfer())
            ->addProductAbstractCategory($productAbstractCategoryStorageTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $categoryStorageClientMock->expandProductCategoriesWithParentIds($productAbstractCategoryStorageCollectionTransfer, 'en_US', 'US');
    }

    /**
     * @return void
     */
    public function testExpandProductCategoriesWithParentIdsThrowsExceptionWhenParentCategoryIdIsMissing(): void
    {
        // Arrange
        $categoryNodeStorageMock = $this->getCategoryNodeStorageMock([
            static::CATEGORY_NODE_ID_ROOT => (new CategoryNodeStorageTransfer())->addParents(
                (new CategoryNodeStorageTransfer()),
            ),
        ]);

        $categoryStorageFactoryMock = $this->getCategoryStorageFactoryMock(['createCategoryNodeStorage']);
        $categoryStorageFactoryMock->method('createCategoryNodeStorage')->willReturn($categoryNodeStorageMock);

        $categoryStorageClientMock = $this->tester->getClientMock($categoryStorageFactoryMock);

        $productAbstractCategoryStorageTransfer = (new ProductAbstractCategoryStorageBuilder([
            ProductAbstractCategoryStorageTransfer::ID_PRODUCT_ABSTRACT => static::PRODUCT_ABSTRACT_ID,
            ProductAbstractCategoryStorageTransfer::CATEGORIES => [
                [
                    ProductCategoryStorageTransfer::CATEGORY_ID => static::CATEGORY_ID_ROOT,
                    ProductCategoryStorageTransfer::CATEGORY_NODE_ID => static::CATEGORY_NODE_ID_ROOT,
                    ProductCategoryStorageTransfer::NAME => static::CATEGORY_NAME_COMPUTER,
                    ProductCategoryStorageTransfer::URL => static::CATEGORY_URL_COMPUTER,
                    ProductCategoryStorageTransfer::PARENT_CATEGORY_IDS => [],
                ],
            ],
        ]))->build();
        $productAbstractCategoryStorageCollectionTransfer = (new ProductAbstractCategoryStorageCollectionTransfer())
            ->addProductAbstractCategory($productAbstractCategoryStorageTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $categoryStorageClientMock->expandProductCategoriesWithParentIds($productAbstractCategoryStorageCollectionTransfer, 'en_US', 'US');
    }

    /**
     * @return void
     */
    public function testExpandProductCategoriesWithParentIds(): void
    {
        // Arrange
        $categoryStorageFactoryMock = $this->getCategoryStorageFactoryMock();

        $categoryStorageClientMock = $this->tester->getClientMock($categoryStorageFactoryMock);

        $productAbstractCategoryStorageTransfer = (new ProductAbstractCategoryStorageBuilder([
            ProductAbstractCategoryStorageTransfer::ID_PRODUCT_ABSTRACT => static::PRODUCT_ABSTRACT_ID,
            ProductAbstractCategoryStorageTransfer::CATEGORIES => [
                0 => [
                    ProductCategoryStorageTransfer::CATEGORY_ID => static::CATEGORY_ID_ROOT,
                    ProductCategoryStorageTransfer::CATEGORY_NODE_ID => static::CATEGORY_NODE_ID_ROOT,
                    ProductCategoryStorageTransfer::NAME => static::CATEGORY_NAME_COMPUTER,
                    ProductCategoryStorageTransfer::URL => static::CATEGORY_URL_COMPUTER,
                    ProductCategoryStorageTransfer::PARENT_CATEGORY_IDS => [],
                ],
            ],
        ]))->build();
        $productAbstractCategoryStorageCollectionTransfer = (new ProductAbstractCategoryStorageCollectionTransfer())
            ->addProductAbstractCategory($productAbstractCategoryStorageTransfer);

        // Act
        $productAbstractCategoryStorageCollectionTransfer = $categoryStorageClientMock->expandProductCategoriesWithParentIds($productAbstractCategoryStorageCollectionTransfer, 'en_US', 'US');

        // Assert
        $productAbstractCategoryStorageTransfers = $productAbstractCategoryStorageCollectionTransfer->getProductAbstractCategories()->getArrayCopy();
        $this->assertCount(1, $productAbstractCategoryStorageTransfers);

        /** @var \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransferNew */
        $productAbstractCategoryStorageTransferNew = array_pop($productAbstractCategoryStorageTransfers);
        /** @var \Generated\Shared\Transfer\ProductCategoryStorageTransfer $productCategoryStorageTransfer */
        $productCategoryStorageTransfer = $productAbstractCategoryStorageTransferNew->getCategories()->offsetGet(0);

        $this->assertCount(1, $productCategoryStorageTransfer->getParentCategoryIds());
        $this->assertContains(3, $productCategoryStorageTransfer->getParentCategoryIds());
    }

    /**
     * @return void
     */
    public function testGetCategoryNodeByIdsShouldFilterOutEmptyData(): void
    {
        // Arrange
        $categoryNodeIds = [static::CATEGORY_NODE_ID_PARENT, static::CATEGORY_NODE_ID_ROOT];
        $categoryStorageData = array_merge($this->getCategoryNodeStorageWithChildAndParentStorageData(), [
            static::TEST_KEY_2 => null,
            static::TEST_KEY_3 => '',
        ]);
        $categoryStorageFactoryMock = $this->getCategoryStorageFactoryMock([], $categoryStorageData);
        $categoryStorageClientMock = $this->tester->getClientMock($categoryStorageFactoryMock);

        // Act
        $categoryNodeData = $categoryStorageClientMock->getCategoryNodeByIds($categoryNodeIds, static::LOCALE_DE, static::STORE_DE);

        // Assert
        $this->assertCount(1, $categoryNodeData);
        $this->assertSame(static::CATEGORY_NODE_ID_ROOT, $categoryNodeData[static::CATEGORY_NODE_ID_ROOT]->getNodeId());
    }

    /**
     * @param list<string> $mockMethods
     * @param array<string, string> $categoryStorageData
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory
     */
    protected function getCategoryStorageFactoryMock(array $mockMethods = [], array $categoryStorageData = []): CategoryStorageFactory
    {
        $mockMethods = array_merge($mockMethods, ['getStorageClient', 'getConfig']);
        $categoryStorageFactoryMock = $this->getMockBuilder(CategoryStorageFactory::class)
            ->onlyMethods($mockMethods)
            ->getMock();

        if ($categoryStorageData === []) {
            $categoryStorageData = $this->getCategoryNodeStorageWithChildAndParentStorageData();
        }

        $categoryStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getStorageClientMock($categoryStorageData));

        return $categoryStorageFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface
     */
    protected function getStorageClientMock(array $categoryStorageData): CategoryStorageToStorageInterface
    {
        $storageClientMock = $this->getMockBuilder(CategoryStorageToStorageBridge::class)
            ->onlyMethods(['get', 'getMulti'])
            ->disableOriginalConstructor()
            ->getMock();

        $storageClientMock
            ->method('getMulti')
            ->willReturn($categoryStorageData);

        return $storageClientMock;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer> $categoryNodeStorageTransfers
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface
     */
    protected function getCategoryNodeStorageMock(array $categoryNodeStorageTransfers = []): CategoryNodeStorageInterface
    {
        $categoryNodeStorageMock = $this->getMockBuilder(CategoryNodeStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $categoryNodeStorageMock->method('getCategoryNodeByIds')
            ->willReturn($categoryNodeStorageTransfers);

        return $categoryNodeStorageMock;
    }

    /**
     * @return array<string, string>
     */
    protected function getCategoryNodeStorageWithChildAndParentStorageData(): array
    {
        $categoryNodeStorageData = (new CategoryNodeStorageBuilder([
                CategoryNodeStorageTransfer::NODE_ID => static::CATEGORY_NODE_ID_ROOT,
                CategoryNodeStorageTransfer::ID_CATEGORY => static::CATEGORY_ID_ROOT,
                CategoryNodeStorageTransfer::CHILDREN => new ArrayObject([
                    (new CategoryNodeStorageBuilder([
                        CategoryNodeStorageTransfer::NODE_ID => static::CATEGORY_NODE_ID_CHILDREN,
                    ]))->build()->toArray(),
                ]),
                CategoryNodeStorageTransfer::PARENTS => new ArrayObject([
                    (new CategoryNodeStorageBuilder([
                        CategoryNodeStorageTransfer::NODE_ID => static::CATEGORY_NODE_ID_PARENT,
                        CategoryNodeStorageTransfer::ID_CATEGORY => static::CATEGORY_ID_PARENT,
                    ]))->build()->toArray(),
                ]),
            ]))->build()->toArray();

        return [static::TEST_KEY => json_encode($categoryNodeStorageData)];
    }
}
