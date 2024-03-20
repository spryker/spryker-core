<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxSetConditionsTransfer;
use Generated\Shared\Transfer\TaxSetCriteriaTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\Tax\Dependency\Facade\TaxToStoreFacadeInterface;
use Spryker\Zed\Tax\TaxDependencyProvider;
use SprykerTest\Zed\Tax\TaxBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Business
 * @group Facade
 * @group TaxFacadeTest
 * Add your own group annotations below this line
 */
class TaxFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Tax\TaxBusinessTester
     */
    protected TaxBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetDefaultTaxCountryIso2CodeReturnsDefaultCountryIso2Code(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === true) {
            $this->markTestSkipped('This test requires DynamicStore to be disabled.');
        }

        // Arrange
        $storeMock = $this->createMock(TaxToStoreFacadeInterface::class);
        $storeMock->method('getCurrentStore')->willReturn(
            (new StoreTransfer())
                ->setCountries($this->tester::COUNTRIES),
        );
        $this->tester->setDependency(
            TaxDependencyProvider::FACADE_STORE,
            $storeMock,
        );

        // Act
        $defaultTaxCountryIso2Code = $this->tester->getFacade()->getDefaultTaxCountryIso2Code();

        // Assert
        $this->assertSame($this->tester::COUNTRIES[0], $defaultTaxCountryIso2Code);
    }

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
        $this->assertTrue($this->assertTaxSetExistsInCollection(
            $taxSetTransfer1,
            $taxSetCollectionTransfer->getTaxSets(),
            0,
        ));
        $this->assertTrue($this->assertTaxSetExistsInCollection(
            $taxSetTransfer2,
            $taxSetCollectionTransfer->getTaxSets(),
            0,
        ));
        $this->assertTrue($this->assertTaxSetExistsInCollection(
            $taxSetTransfer3,
            $taxSetCollectionTransfer->getTaxSets(),
            0,
        ));
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
        $this->assertTrue($this->assertTaxSetExistsInCollection(
            $taxSetTransfer1,
            $taxSetCollectionTransfer->getTaxSets(),
            1,
        ));
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
        $taxSetTransfers = [];
        $taxSetTransfers[] = $this->tester->haveTaxSetWithTaxRates();
        $taxSetTransfers[] = $this->tester->haveTaxSetWithTaxRates();
        $taxSetTransfers[] = $this->tester->haveTaxSetWithTaxRates();
        $taxSetTransfers[] = $this->tester->haveTaxSetWithTaxRates();
        $taxSetCriteriaTransfer = (new TaxSetCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setOffset(1)->setLimit(2),
            );

        // Act
        $taxSetCollectionTransfer = $this->tester->getFacade()->getTaxSetCollection($taxSetCriteriaTransfer);

        // Assert
        $this->assertCount(2, $taxSetCollectionTransfer->getTaxSets());
        $this->assertSame(4, $taxSetCollectionTransfer->getPagination()->getNbResults());

        $foundTransfers = 0;
        foreach ($taxSetTransfers as $taxSetTransfer) {
            if ($this->assertTaxSetExistsInCollection($taxSetTransfer, $taxSetCollectionTransfer->getTaxSets())) {
                $foundTransfers++;
            }
        }

        $this->assertEquals(2, $foundTransfers);
    }

    /**
     * @return void
     */
    public function testGetTaxSetCollectionFiltersTaxSetsByName(): void
    {
        // Arrange
        $taxSetTransfer = $this->tester->haveTaxSet();

        $taxSetCriteriaTransfer = (new TaxSetCriteriaTransfer())->setTaxSetConditions(
            (new TaxSetConditionsTransfer())
                ->addName($taxSetTransfer->getName()),
        );

        // Act
        $taxSetCollectionTransfer = $this->tester->getFacade()
            ->getTaxSetCollection($taxSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $taxSetCollectionTransfer->getTaxSets());
        $this->assertTrue($this->assertTaxSetExistsInCollection(
            $taxSetTransfer,
            $taxSetCollectionTransfer->getTaxSets(),
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     * @param \ArrayObject|array<\Generated\Shared\Transfer\TaxSetTransfer> $taxSets
     * @param int|null $taxRateCount
     *
     * @return bool
     */
    protected function assertTaxSetExistsInCollection(
        TaxSetTransfer $taxSetTransfer,
        ArrayObject $taxSets,
        ?int $taxRateCount = null
    ): bool {
        foreach ($taxSets as $taxSet) {
            if ($taxSetTransfer->getIdTaxSet() === $taxSet->getIdTaxSet()) {
                $this->assertEquals($taxSetTransfer->getName(), $taxSet->getName());
                if ($taxRateCount !== null) {
                    $this->assertCount($taxRateCount, $taxSet->getTaxRates());
                }

                return true;
            }
        }

        return false;
    }
}
