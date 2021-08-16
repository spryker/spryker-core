<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationCart
 * @group Business
 * @group ProductConfigurationCartFacade
 * @group ExpandPriceProductTransfersWithProductConfigurationPricesTest
 * Add your own group annotations below this line
 */
class ExpandPriceProductTransfersWithProductConfigurationPricesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductConfigurationCart\ProductConfigurationCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandPriceProductTransfersWithProductConfigurationPricesWillExpandPrices(): void
    {
        //Arrange
        $priceProductTransfers = new ArrayObject();
        $priceProductTransfers->append((new PriceProductTransfer())->setSkuProduct('test1'));
        $productConfigurationInstance = (new ProductConfigurationInstanceTransfer())->setPrices($priceProductTransfers);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstance,
        ]))->build();

        $cartChangeTransfer = (new CartChangeTransfer())->addItem($itemTransfer);

        //Act
        $priceProductTransfersResult = $this->tester->getFacade()
            ->expandPriceProductTransfersWithProductConfigurationPrices(
                [(new PriceProductTransfer())->setSkuProduct('test2')],
                $cartChangeTransfer
            );

        //Assert
        $this->assertCount(
            2,
            $priceProductTransfersResult,
            'Expects that prices will be expanded with product configuration prices.'
        );
    }

    /**
     * @return void
     */
    public function testExpandPriceProductTransfersWithProductConfigurationPricesWillDoNothingWithoutProductConfigurationExistence(): void
    {
        //Arrange
        $itemTransfer = (new ItemBuilder())->build();

        $cartChangeTransfer = (new CartChangeTransfer())->addItem($itemTransfer);

        //Act
        $priceProductTransfersResult = $this->tester->getFacade()
            ->expandPriceProductTransfersWithProductConfigurationPrices(
                [(new PriceProductTransfer())->setSkuProduct('test2')],
                $cartChangeTransfer
            );

        //Assert
        $this->assertCount(
            1,
            $priceProductTransfersResult,
            'Expects that prices wont be changed when no product configuration.'
        );
    }
}
