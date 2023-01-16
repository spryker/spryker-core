<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinued\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedConditionsTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use SprykerTest\Zed\ProductDiscontinued\ProductDiscontinuedBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductDiscontinued
 * @group Business
 * @group Facade
 * @group GetProductDiscontinuedCollectionTest
 * Add your own group annotations below this line
 */
class GetProductDiscontinuedCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_PRODUCT_DISCONTINUED = -1;

    /**
     * @var int
     */
    protected const ID_LOCALE = 1;

    /**
     * @var \SprykerTest\Zed\ProductDiscontinued\ProductDiscontinuedBusinessTester
     */
    protected ProductDiscontinuedBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetProductDiscontinuedCollectionWhileNoCriteriaMatchedReturnsEmptyCollection(): void
    {
        // Arrange
        $this->tester->ensureProductDiscontinuedTableIsEmpty();
        $this->tester->createProductDiscontinued();
        $this->tester->createProductDiscontinued();

        $productDiscontinuedCriteriaTransfer = (new ProductDiscontinuedCriteriaTransfer())
            ->setProductDiscontinuedConditions(
                (new ProductDiscontinuedConditionsTransfer())
                    ->addIdProductDiscontinued(static::ID_PRODUCT_DISCONTINUED),
            );

        // Act
        $productDiscontinuedCollectionTransfer = $this->tester->getFacade()
            ->getProductDiscontinuedCollection($productDiscontinuedCriteriaTransfer);

        // Assert
        $this->assertCount(0, $productDiscontinuedCollectionTransfer->getDiscontinuedProducts());
    }

    /**
     * @return void
     */
    public function testGetProductDiscontinuedCollectionReturnsCollectionWithOneProductDiscontinuedWhileAllCriteriasMatched(): void
    {
        // Arrange
        $this->tester->ensureProductDiscontinuedTableIsEmpty();
        $productDiscontinuedTransfer = $this->tester->createProductDiscontinued();
        $this->tester->createProductDiscontinued();

        $productDiscontinuedCriteriaTransfer = (new ProductDiscontinuedCriteriaTransfer())
            ->setProductDiscontinuedConditions(
                (new ProductDiscontinuedConditionsTransfer())
                    ->addIdProductDiscontinued($productDiscontinuedTransfer->getIdProductDiscontinued())
                    ->addSku($productDiscontinuedTransfer->getSku())
                    ->addIdProduct($productDiscontinuedTransfer->getFkProduct()),
            );

        // Act
        $productDiscontinuedCollectionTransfer = $this->tester->getFacade()
            ->getProductDiscontinuedCollection($productDiscontinuedCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productDiscontinuedCollectionTransfer->getDiscontinuedProducts());
        $this->assertSame(
            $productDiscontinuedTransfer->getIdProductDiscontinued(),
            $productDiscontinuedCollectionTransfer->getDiscontinuedProducts()->getIterator()->current()->getIdProductDiscontinued(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductDiscontinuedCollectionReturnsCollectionWithTwoProductsDiscontinuedWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        $this->tester->ensureProductDiscontinuedTableIsEmpty();
        for ($i = 0; $i < 4; $i++) {
            $this->tester->createProductDiscontinued();
        }

        $productDiscontinuedCriteriaTransfer = (new ProductDiscontinuedCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(2)->setOffset(1),
            );

        // Act
        $productDiscontinuedCollectionTransfer = $this->tester->getFacade()->getProductDiscontinuedCollection($productDiscontinuedCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productDiscontinuedCollectionTransfer->getDiscontinuedProducts());
        $this->assertSame(4, $productDiscontinuedCollectionTransfer->getPagination()->getNbResults());
    }

    /**
     * @return void
     */
    public function testGetProductDiscontinuedCollectionReturnsCollectionWithOneProductDiscontinuedNote(): void
    {
        // Arrange
        $this->tester->ensureProductDiscontinuedTableIsEmpty();
        $productDiscontinuedTransfer = $this->tester->createProductDiscontinued();
        $productDiscontinuedNoteTransfer = $this->tester->createProductDiscontinuedNote($productDiscontinuedTransfer, [
            ProductDiscontinuedNoteTransfer::FK_LOCALE => static::ID_LOCALE,
        ]);

        $productDiscontinuedCriteriaTransfer = (new ProductDiscontinuedCriteriaTransfer())
            ->setWithProductDiscontiniuedNotes(true)
            ->setProductDiscontinuedConditions(
                (new ProductDiscontinuedConditionsTransfer())
                    ->addIdProductDiscontinued(
                        $productDiscontinuedTransfer->getIdProductDiscontinued(),
                    ),
            );

        // Act
        $productDiscontinuedCollectionTransfer = $this->tester->getFacade()
            ->getProductDiscontinuedCollection($productDiscontinuedCriteriaTransfer);

        // Assert
        $productDiscontinuedNoteTransfers = $productDiscontinuedCollectionTransfer->getDiscontinuedProducts()
            ->getIterator()->current()->getProductDiscontinuedNotes();
        $this->assertCount(1, $productDiscontinuedNoteTransfers);
        $this->assertSame(
            $productDiscontinuedNoteTransfer->getNote(),
            $productDiscontinuedNoteTransfers->getIterator()->current()->getNote(),
        );
    }
}
