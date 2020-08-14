<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManager;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Persistence
 * @group PriceProductEntityManagerTest
 * Add your own group annotations below this line
 */
class PriceProductEntityManagerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSavePriceProductAbstract(): void
    {
        //Arrange
        $priceProductTransfer = $this->createPriceProductForProductAbstract();
        $expectedIdPriceProduct = $priceProductTransfer->getIdPriceProduct();

        //Act
        $actualIdPriceProduct = $this->getEntityManager()->savePriceProductForProductAbstract($priceProductTransfer);

        //Assert
        $this->assertSame($expectedIdPriceProduct, $actualIdPriceProduct);
    }

    /**
     * @return void
     */
    public function testSavePriceProductConcrete(): void
    {
        //Arrange
        $priceProductTransfer = $this->createPriceProductForProductConcret();
        $expectedIdPriceProduct = $priceProductTransfer->getIdPriceProduct();

        //Act
        $actualIdPriceProduct = $this->getEntityManager()->savePriceProductForProductConcrete($priceProductTransfer);

        //Assert
        $this->assertSame($expectedIdPriceProduct, $actualIdPriceProduct);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductForProductConcret(): PriceProductTransfer
    {
        $productConcrete = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->havePriceProduct([
                'idProduct' => $productConcrete->getIdProductConcrete(),
                'skuProductAbstract' => $productConcrete->getAbstractSku(),
            ]);
        $priceProductTransfer->setSkuProductAbstract($productConcrete->getSku());

        return $priceProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductForProductAbstract(): PriceProductTransfer
    {
        $productAbstract = $this->tester->haveProductAbstract();
        $priceProductTransfer = $this->tester->havePriceProduct(['skuProductAbstract' => $productAbstract->getSku()]);
        $priceProductTransfer->setSkuProductAbstract($productAbstract->getSku());

        return $priceProductTransfer;
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    public function getEntityManager(): PriceProductEntityManagerInterface
    {
        return new PriceProductEntityManager();
    }
}
