<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationCart
 * @group Business
 * @group ProductConfigurationCartFacade
 * @group CountItemQuantityTest
 * Add your own group annotations below this line
 */
class CountItemQuantityTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductConfigurationCart\ProductConfigurationCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCountItemQuantityWithoutItemsInCartWillReturnDefaultQuantity(): void
    {
        //Arrange
        $itemTransfer = (new ItemBuilder())->build();
        $cartChangeTransfer = (new CartChangeBuilder())->withQuote()->build();

        //Act
        $cartItemQuantity = $this->tester->getFacade()->countItemQuantity($cartChangeTransfer, $itemTransfer);

        //Assert
        $this->assertSame(
            0,
            $cartItemQuantity->getQuantity(),
            'Expects that default cart item quantity when no items in the cart.'
        );
    }

    /**
     * @return void
     */
    public function testCountItemQuantityWillCountQuantityCorrectly(): void
    {
        //Arrange
        $itemTransferInCartOne = (new ItemBuilder([ItemTransfer::QUANTITY => 3]))->build();
        $itemTransferInCartTwo = (new ItemBuilder([ItemTransfer::QUANTITY => 10]))->build();
        $itemTransferAddedToCart = (clone $itemTransferInCartOne)->setQuantity(5);

        $cartChangeTransfer = (new CartChangeBuilder())->withQuote()->build();
        $cartChangeTransfer->getQuote()->setItems(new ArrayObject([$itemTransferInCartOne, $itemTransferInCartTwo]));

        //Act
        $cartItemQuantity = $this->tester->getFacade()
            ->countItemQuantity($cartChangeTransfer, $itemTransferAddedToCart);

        //Assert
        $this->assertSame(
            3,
            $cartItemQuantity->getQuantity(),
            'Expects that item quantity will be counted correctly.'
        );
    }
}
