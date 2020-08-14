<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Persistence;

use Codeception\Test\Unit;
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
    public function testSavePriceProductAbstractSavePriceProductForProductAbstract(): void
    {
        //Arrange
        $priceProductTransfer = $this->tester->createPriceProductForProductAbstract();
        $expectedIdPriceProduct = $priceProductTransfer->getIdPriceProduct();

        //Act
        $actualIdPriceProduct = $this->getEntityManager()->savePriceProductForProductAbstract($priceProductTransfer);

        //Assert
        $this->assertSame($expectedIdPriceProduct, $actualIdPriceProduct);
    }

    /**
     * @return void
     */
    public function testSavePriceProductConcreteSavePriceProductForProductConcrete(): void
    {
        //Arrange
        $priceProductTransfer = $this->tester->createPriceProductForProductConcrete();
        $expectedIdPriceProduct = $priceProductTransfer->getIdPriceProduct();

        //Act
        $actualIdPriceProduct = $this->getEntityManager()->savePriceProductForProductConcrete($priceProductTransfer);

        //Assert
        $this->assertSame($expectedIdPriceProduct, $actualIdPriceProduct);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected function getEntityManager(): PriceProductEntityManagerInterface
    {
        return new PriceProductEntityManager();
    }
}
