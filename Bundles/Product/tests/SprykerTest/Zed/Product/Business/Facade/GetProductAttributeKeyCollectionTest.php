<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyConditionsTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\Product\ProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Facade
 * @group GetProductAttributeKeyCollectionTest
 * Add your own group annotations below this line
 */
class GetProductAttributeKeyCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const KEY_IS_SUPER = 'is_super';

    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected ProductBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductAttributeKeyTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testReturnsCollectionFilteredByKeys(): void
    {
        // Arrange
        $productAttributeKey1Entity = $this->tester->haveProductAttributeKeyEntity();
        $productAttributeKey2Entity = $this->tester->haveProductAttributeKeyEntity();
        $this->tester->haveProductAttributeKeyEntity();
        $this->tester->haveProductAttributeKeyEntity();

        $productAttributeKeyConditionsTransfer = (new ProductAttributeKeyConditionsTransfer())
            ->addKey($productAttributeKey1Entity->getKey())
            ->addKey($productAttributeKey2Entity->getKey());
        $productAttributeKeyCriteriaTransfer = (new ProductAttributeKeyCriteriaTransfer())
            ->setProductAttributeKeyConditions($productAttributeKeyConditionsTransfer);

        // Act
        $productAttributeKeyCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAttributeKeyCollection($productAttributeKeyCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productAttributeKeyCollectionTransfer->getProductAttributeKeys());
        $this->assertTrue($this->isProductAttributeKeyInCollection($productAttributeKeyCollectionTransfer, $productAttributeKey1Entity->getKey()));
        $this->assertTrue($this->isProductAttributeKeyInCollection($productAttributeKeyCollectionTransfer, $productAttributeKey2Entity->getKey()));
    }

    /**
     * @return void
     */
    public function testReturnsCollectionFilteredIsSuperFlag(): void
    {
        // Arrange
        $productAttributeKey1Entity = $this->tester->haveProductAttributeKeyEntity([static::KEY_IS_SUPER => true]);
        $productAttributeKey2Entity = $this->tester->haveProductAttributeKeyEntity([static::KEY_IS_SUPER => true]);
        $this->tester->haveProductAttributeKeyEntity([static::KEY_IS_SUPER => false]);
        $this->tester->haveProductAttributeKeyEntity([static::KEY_IS_SUPER => false]);

        $productAttributeKeyConditionsTransfer = (new ProductAttributeKeyConditionsTransfer())
            ->setIsSuper(true);
        $productAttributeKeyCriteriaTransfer = (new ProductAttributeKeyCriteriaTransfer())
            ->setProductAttributeKeyConditions($productAttributeKeyConditionsTransfer);

        // Act
        $productAttributeKeyCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAttributeKeyCollection($productAttributeKeyCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productAttributeKeyCollectionTransfer->getProductAttributeKeys());
        $this->assertTrue($this->isProductAttributeKeyInCollection($productAttributeKeyCollectionTransfer, $productAttributeKey1Entity->getKey()));
        $this->assertTrue($this->isProductAttributeKeyInCollection($productAttributeKeyCollectionTransfer, $productAttributeKey2Entity->getKey()));
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $this->tester->haveProductAttributeKeyEntity();
        $this->tester->haveProductAttributeKeyEntity();
        $productAttributeKey1Entity = $this->tester->haveProductAttributeKeyEntity();
        $productAttributeKey2Entity = $this->tester->haveProductAttributeKeyEntity();
        $this->tester->haveProductAttributeKeyEntity();

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(2)
            ->setLimit(2);

        $productAttributeKeyCriteriaTransfer = (new ProductAttributeKeyCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $productAttributeKeyCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAttributeKeyCollection($productAttributeKeyCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productAttributeKeyCollectionTransfer->getProductAttributeKeys());
        $this->assertTrue($this->isProductAttributeKeyInCollection($productAttributeKeyCollectionTransfer, $productAttributeKey1Entity->getKey()));
        $this->assertTrue($this->isProductAttributeKeyInCollection($productAttributeKeyCollectionTransfer, $productAttributeKey2Entity->getKey()));

        $this->assertNotNull($productAttributeKeyCollectionTransfer->getPagination());
        $this->assertSame(5, $productAttributeKeyCollectionTransfer->getPaginationOrFail()->getNbResults());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->tester->haveProductAttributeKeyEntity();
        $this->tester->haveProductAttributeKeyEntity();
        $productAttributeKey1Entity = $this->tester->haveProductAttributeKeyEntity();
        $productAttributeKey2Entity = $this->tester->haveProductAttributeKeyEntity();
        $this->tester->haveProductAttributeKeyEntity();

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        $productAttributeKeyCriteriaTransfer = (new ProductAttributeKeyCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $productAttributeKeyCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAttributeKeyCollection($productAttributeKeyCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productAttributeKeyCollectionTransfer->getProductAttributeKeys());
        $this->assertTrue($this->isProductAttributeKeyInCollection($productAttributeKeyCollectionTransfer, $productAttributeKey1Entity->getKey()));
        $this->assertTrue($this->isProductAttributeKeyInCollection($productAttributeKeyCollectionTransfer, $productAttributeKey2Entity->getKey()));

        $this->assertNotNull($productAttributeKeyCollectionTransfer->getPagination());
        $paginationTransfer = $productAttributeKeyCollectionTransfer->getPaginationOrFail();
        $this->assertSame(2, $paginationTransfer->getPage());
        $this->assertSame(2, $paginationTransfer->getMaxPerPage());
        $this->assertSame(5, $paginationTransfer->getNbResults());
        $this->assertSame(3, $paginationTransfer->getFirstIndex());
        $this->assertSame(4, $paginationTransfer->getLastIndex());
        $this->assertSame(1, $paginationTransfer->getFirstPage());
        $this->assertSame(3, $paginationTransfer->getLastPage());
        $this->assertSame(3, $paginationTransfer->getNextPage());
        $this->assertSame(1, $paginationTransfer->getPreviousPage());
    }

    /**
     * @return void
     */
    public function testReturnsShipmentTypesSortedByKeyFieldDesc(): void
    {
        // Arrange
        $this->tester->haveProductAttributeKeyEntity([ProductAttributeKeyTransfer::KEY => 'abc']);
        $this->tester->haveProductAttributeKeyEntity([ProductAttributeKeyTransfer::KEY => 'def']);
        $this->tester->haveProductAttributeKeyEntity([ProductAttributeKeyTransfer::KEY => 'ghi']);

        $sortTransfer = (new SortTransfer())
            ->setField(ProductAttributeKeyTransfer::KEY)
            ->setIsAscending(false);

        $productAttributeKeyCriteriaTransfer = (new ProductAttributeKeyCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $productAttributeKeyCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAttributeKeyCollection($productAttributeKeyCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productAttributeKeyCollectionTransfer->getProductAttributeKeys());
        $productAttributeKeysIterator = $productAttributeKeyCollectionTransfer->getProductAttributeKeys()->getIterator();
        $this->assertSame('ghi', $productAttributeKeysIterator->offsetGet(0)->getKeyOrFail());
        $this->assertSame('def', $productAttributeKeysIterator->offsetGet(1)->getKeyOrFail());
        $this->assertSame('abc', $productAttributeKeysIterator->offsetGet(2)->getKeyOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsShipmentTypesSortedByKeyFieldAsc(): void
    {
        // Arrange
        $this->tester->haveProductAttributeKeyEntity([ProductAttributeKeyTransfer::KEY => 'abc']);
        $this->tester->haveProductAttributeKeyEntity([ProductAttributeKeyTransfer::KEY => 'def']);
        $this->tester->haveProductAttributeKeyEntity([ProductAttributeKeyTransfer::KEY => 'ghi']);

        $sortTransfer = (new SortTransfer())
            ->setField(ProductAttributeKeyTransfer::KEY)
            ->setIsAscending(true);

        $productAttributeKeyCriteriaTransfer = (new ProductAttributeKeyCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $productAttributeKeyCollectionTransfer = $this->tester->getProductFacade()
            ->getProductAttributeKeyCollection($productAttributeKeyCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productAttributeKeyCollectionTransfer->getProductAttributeKeys());
        $productAttributeKeysIterator = $productAttributeKeyCollectionTransfer->getProductAttributeKeys()->getIterator();
        $this->assertSame('abc', $productAttributeKeysIterator->offsetGet(0)->getKeyOrFail());
        $this->assertSame('def', $productAttributeKeysIterator->offsetGet(1)->getKeyOrFail());
        $this->assertSame('ghi', $productAttributeKeysIterator->offsetGet(2)->getKeyOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer $productAttributeKeyCollectionTransfer
     * @param string $key
     *
     * @return bool
     */
    protected function isProductAttributeKeyInCollection(ProductAttributeKeyCollectionTransfer $productAttributeKeyCollectionTransfer, string $key): bool
    {
        foreach ($productAttributeKeyCollectionTransfer->getProductAttributeKeys() as $productAttributeKeyTransfer) {
            if ($productAttributeKeyTransfer->getKeyOrFail() === $key) {
                return true;
            }
        }

        return false;
    }
}
