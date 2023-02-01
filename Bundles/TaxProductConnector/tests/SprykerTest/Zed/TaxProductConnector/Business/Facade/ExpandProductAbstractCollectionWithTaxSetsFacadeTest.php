<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractRelationsTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TaxProductConnector
 * @group Business
 * @group Facade
 * @group ExpandProductAbstractCollectionWithTaxSetsFacadeTest
 * Add your own group annotations below this line
 */
class ExpandProductAbstractCollectionWithTaxSetsFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\TaxProductConnector\TaxProductConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductAbstractCollectionWithTaxSetsFacadeAddsTaxSetDataToCollection(): void
    {
        $expectedTaxSetTransfer = $this->tester->haveTaxSetWithTaxRates();
        $expectedProductAbstractTransfer = $this->tester->createProductWithTaxSetInDb($expectedTaxSetTransfer);

        $expectedProductAbstractCollectionTransfer = (new ProductAbstractCollectionTransfer())
            ->addProductAbstract($expectedProductAbstractTransfer);
        $expectedProductAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setProductAbstractRelations(
                (new ProductAbstractRelationsTransfer())
                    ->setWithTaxSet(true),
            );

        $productAbstractCollectionTransfer = $this->tester->getFacade()
            ->expandProductAbstractCollectionWithTaxSets($expectedProductAbstractCollectionTransfer, $expectedProductAbstractCriteriaTransfer);

        $this->assertSame(
            $expectedProductAbstractTransfer->getSku(),
            $productAbstractCollectionTransfer->getProductTaxSets()->offsetGet(0)->getProductAbstractSku(),
        );
        $this->assertSame(
            $expectedTaxSetTransfer->getIdTaxSet(),
            $productAbstractCollectionTransfer->getProductTaxSets()->offsetGet(0)->getTaxSet()->getIdTaxSet(),
        );
        $this->assertNotEmpty(
            $productAbstractCollectionTransfer->getProductTaxSets()->offsetGet(0)->getTaxSet()->getName(),
        );
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractCollectionWithTaxSetsFacadeDoesNothingWhenProductHsNoTaxSet(): void
    {
        $expectedProductAbstractTransfer = $this->tester->haveProductAbstract();

        $expectedProductAbstractCollectionTransfer = (new ProductAbstractCollectionTransfer())
            ->addProductAbstract($expectedProductAbstractTransfer);
        $expectedProductAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setProductAbstractRelations(
                (new ProductAbstractRelationsTransfer())
                    ->setWithTaxSet(true),
            );

        $productAbstractCollectionTransfer = $this->tester->getFacade()
            ->expandProductAbstractCollectionWithTaxSets($expectedProductAbstractCollectionTransfer, $expectedProductAbstractCriteriaTransfer);

        $this->assertEmpty($productAbstractCollectionTransfer->getProductTaxSets());
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractCollectionWithTaxSetsFacadeDoesNothingWhenCollectionIsEmpty(): void
    {
        $expectedProductAbstractCollectionTransfer = new ProductAbstractCollectionTransfer();
        $expectedProductAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setProductAbstractRelations(
                (new ProductAbstractRelationsTransfer())
                    ->setWithTaxSet(true),
            );

        $productAbstractCollectionTransfer = $this->tester->getFacade()
            ->expandProductAbstractCollectionWithTaxSets($expectedProductAbstractCollectionTransfer, $expectedProductAbstractCriteriaTransfer);

        $this->assertEmpty($productAbstractCollectionTransfer->getProductTaxSets());
    }
}
