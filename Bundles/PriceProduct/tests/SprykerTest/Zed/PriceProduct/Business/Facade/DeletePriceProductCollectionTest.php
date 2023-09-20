<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCollectionResponseTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group DeletePriceProductCollectionTest
 * Add your own group annotations below this line
 */
class DeletePriceProductCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testDeletePriceProductCollectionDeletesPriceProductDefaultCollectionByPriceProductDefaultIds()
    {
        // Arrange
        $productConcreteTransfer1 = $this->tester->haveProduct();
        $priceProductTransfer1 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer1->getAbstractSku(),
        ]);
        $productConcreteTransfer2 = $this->tester->haveProduct();
        $priceProductTransfer2 = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer2->getAbstractSku(),
        ]);
        $idPriceProductDefault1 = $priceProductTransfer1->getPriceDimensionOrFail()->getIdPriceProductDefault();
        $idPriceProductDefault2 = $priceProductTransfer2->getPriceDimensionOrFail()->getIdPriceProductDefault();

        $priceProductCollectionDeleteCriteriaTransfer = (new PriceProductCollectionDeleteCriteriaTransfer())
            ->setPriceProductDefaultIds([
                $idPriceProductDefault1,
                $idPriceProductDefault2,
            ]);

        // Act
        $priceProductCollectionResponseTransfer = $this->tester->getFacade()
            ->deletePriceProductCollection($priceProductCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(PriceProductCollectionResponseTransfer::class, $priceProductCollectionResponseTransfer);
        $this->assertEmpty($this->tester->findPriceProductDefaults([$idPriceProductDefault1, $idPriceProductDefault1])->count());
    }

    /**
     * @return void
     */
    public function testDeletePriceProductCollectionDoesNothingWhenPriceProductDefaultIdIsMissing()
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
        ]);
        $idPriceProductStore = (int)$this->tester->havePriceProductStore($priceProductTransfer);

        $idPriceProductDefault = $priceProductTransfer->getPriceDimensionOrFail()->getIdPriceProductDefault();

        $priceProductCollectionDeleteCriteriaTransfer = (new PriceProductCollectionDeleteCriteriaTransfer())
            ->setPriceProductStoreIds([
                $idPriceProductStore,
            ]);

        // Act
        $priceProductCollectionResponseTransfer = $this->tester->getFacade()
            ->deletePriceProductCollection($priceProductCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(PriceProductCollectionResponseTransfer::class, $priceProductCollectionResponseTransfer);
        $this->assertEquals(1, $this->tester->findPriceProductDefaults([$idPriceProductDefault])->count());
    }
}
