<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\TaxSetCriteriaTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use SprykerTest\Zed\Tax\TaxBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Business
 * @group Facade
 * @group GetTaxSetCollectionTest
 * Add your own group annotations below this line
 */
class GetTaxSetCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Tax\TaxBusinessTester
     */
    protected TaxBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetTaxSetCollectionReturnsCorrectTaxSetsWithoutPaginationAndRelations(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyTaxSetQuery::create());
        $taxSetTransfer1 = $this->tester->haveTaxSetWithTaxRates();
        $taxSetTransfer2 = $this->tester->haveTaxSetWithTaxRates();
        $taxSetTransfer3 = $this->tester->haveTaxSetWithTaxRates();
        $taxSetCriteriaTransfer = new TaxSetCriteriaTransfer();

        // Act
        $taxSetCollectionTransfer = $this->tester->getFacade()->getTaxSetCollection($taxSetCriteriaTransfer);

        // Assert
        $this->assertCount(3, $taxSetCollectionTransfer->getTaxSets());
        $this->assertSame(
            $taxSetTransfer1->getIdTaxSet(),
            $taxSetCollectionTransfer->getTaxSets()->offsetGet(0)->getIdTaxSet(),
        );
        $this->assertCount(0, $taxSetCollectionTransfer->getTaxSets()->offsetGet(0)->getTaxRates());
        $this->assertSame(
            $taxSetTransfer2->getIdTaxSet(),
            $taxSetCollectionTransfer->getTaxSets()->offsetGet(1)->getIdTaxSet(),
        );
        $this->assertCount(0, $taxSetCollectionTransfer->getTaxSets()->offsetGet(1)->getTaxRates());
        $this->assertSame(
            $taxSetTransfer3->getIdTaxSet(),
            $taxSetCollectionTransfer->getTaxSets()->offsetGet(2)->getIdTaxSet(),
        );
        $this->assertCount(0, $taxSetCollectionTransfer->getTaxSets()->offsetGet(2)->getTaxRates());
    }

    /**
     * @return void
     */
    public function testGetTaxSetCollectionShouldReturnTaxSetsWithRelations(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyTaxSetQuery::create());
        $taxSetTransfer1 = $this->tester->haveTaxSetWithTaxRates();
        $taxSetCriteriaTransfer = (new TaxSetCriteriaTransfer())->setWithTaxRates(true);

        // Act
        $taxSetCollectionTransfer = $this->tester->getFacade()->getTaxSetCollection($taxSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $taxSetCollectionTransfer->getTaxSets());
        $this->assertSame(
            $taxSetTransfer1->getIdTaxSet(),
            $taxSetCollectionTransfer->getTaxSets()->offsetGet(0)->getIdTaxSet(),
        );
        $this->assertCount(1, $taxSetCollectionTransfer->getTaxSets()->offsetGet(0)->getTaxRates());
        $this->assertSame(
            $taxSetTransfer1->getTaxRates()->offsetGet(0)->getIdTaxRate(),
            $taxSetCollectionTransfer->getTaxSets()->offsetGet(0)->getTaxRates()->offsetGet(0)->getIdTaxRate(),
        );
    }

    /**
     * @return void
     */
    public function testGetTaxSetCollectionReturnsPaginatedTaxSetsWithLimitAndOffset(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyTaxSetQuery::create());
        $this->tester->haveTaxSetWithTaxRates();
        $taxSetTransfer1 = $this->tester->haveTaxSetWithTaxRates();
        $taxSetTransfer2 = $this->tester->haveTaxSetWithTaxRates();
        $this->tester->haveTaxSetWithTaxRates();
        $taxSetCriteriaTransfer = (new TaxSetCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setOffset(1)->setLimit(2),
            );

        // Act
        $taxSetCollectionTransfer = $this->tester->getFacade()->getTaxSetCollection($taxSetCriteriaTransfer);

        // Assert
        $this->assertCount(2, $taxSetCollectionTransfer->getTaxSets());
        $this->assertSame(4, $taxSetCollectionTransfer->getPagination()->getNbResults());
        $this->assertSame(
            $taxSetTransfer1->getIdTaxSet(),
            $taxSetCollectionTransfer->getTaxSets()->offsetGet(0)->getIdTaxSet(),
        );
        $this->assertSame(
            $taxSetTransfer2->getIdTaxSet(),
            $taxSetCollectionTransfer->getTaxSets()->offsetGet(1)->getIdTaxSet(),
        );
    }
}
