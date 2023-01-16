<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternative\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAlternativeCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerTest\Zed\ProductAlternative\ProductAlternativeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductAlternative
 * @group Business
 * @group Facade
 * @group GetProductAlternativeCollectionTest
 * Add your own group annotations below this line
 */
class GetProductAlternativeCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductAlternative\ProductAlternativeBusinessTester
     */
    protected ProductAlternativeBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetProductAlternativeCollectionReturnsCorrectProductAlternativesWithoutPagination(): void
    {
        // Arrange
        $this->tester->ensureProductAlternativeTableIsEmpty();

        $productConcreteTransferWithAlternative1 = $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => '123',
        ]);
        $alternativeProductConcreteTransfer1 = $this->tester->haveProduct();
        $productAlternativeTransfer1 = $this->tester->haveProductAlternative($productConcreteTransferWithAlternative1, $alternativeProductConcreteTransfer1->getSku());

        $productConcreteTransferWithAlternative2 = $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => '1234',
        ]);
        $alternativeProductConcreteTransfer2 = $this->tester->haveProduct();
        $productAlternativeTransfer2 = $this->tester->haveProductAlternative($productConcreteTransferWithAlternative2, $alternativeProductConcreteTransfer2->getSku());

        $productAlternativeCriteriaTransfer = new ProductAlternativeCriteriaTransfer();

        // Act
        $productAlternativeCollectionTransfer = $this->tester->getFacade()->getProductAlternativeCollection($productAlternativeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productAlternativeCollectionTransfer->getProductAlternatives());
        $this->assertSame(
            $productAlternativeTransfer1->getIdProductConcrete(),
            $productAlternativeCollectionTransfer->getProductAlternatives()->offsetGet(0)->getIdProduct(),
        );
        $this->assertSame(
            $productAlternativeTransfer2->getIdProductConcrete(),
            $productAlternativeCollectionTransfer->getProductAlternatives()->offsetGet(1)->getIdProduct(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductAlternativeCollectionReturnsCollectionWithFiveProductAlternativesWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        $this->tester->ensureProductAlternativeTableIsEmpty();
        for ($i = 0; $i < 2; $i++) {
            $productConcreteTransferWithAlternative = $this->tester->haveProduct();
            $alternativeProductConcreteTransfer = $this->tester->haveProduct();
            $this->tester->haveProductAlternative($productConcreteTransferWithAlternative, $alternativeProductConcreteTransfer->getSku());
        }

        $productAlternativeCriteriaTransfer = (new ProductAlternativeCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(1)->setOffset(1),
            );

        // Act
        $productAlternativeCollectionTransfer = $this->tester->getFacade()->getProductAlternativeCollection($productAlternativeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productAlternativeCollectionTransfer->getProductAlternatives());
        $this->assertSame(2, $productAlternativeCollectionTransfer->getPagination()->getNbResults());
    }
}
