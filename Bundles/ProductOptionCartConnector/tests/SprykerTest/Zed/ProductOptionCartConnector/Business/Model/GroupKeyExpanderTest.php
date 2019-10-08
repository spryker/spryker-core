<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionCartConnector\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductOptionCartConnector\Business\Model\GroupKeyExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOptionCartConnector
 * @group Business
 * @group Model
 * @group GroupKeyExpanderTest
 * Add your own group annotations below this line
 */
class GroupKeyExpanderTest extends Unit
{
    /**
     * @return void
     */
    public function testKeyGroupByProvidedOptions()
    {
        $changeTransfer = $this->createCartChangeTransfer(
            [
                'cartItem1' => [
                   (new ProductOptionTransfer())->setIdProductOptionValue(2),
                   (new ProductOptionTransfer())->setIdProductOptionValue(1),
                ],
            ]
        );

        $groupKeyExpander = new GroupKeyExpander();
        $groupKeyExpander->expand($changeTransfer);

        $cartItem = $changeTransfer->getItems()[0];
        $this->assertEquals('1-2', $cartItem->getGroupKey());
    }

    /**
     * @return void
     */
    public function testWithExistingGroupKey()
    {
        $changeTransfer = $this->createCartChangeTransfer(
            [
                'cartItem1' => [
                   (new ProductOptionTransfer())->setIdProductOptionValue(2),
                   (new ProductOptionTransfer())->setIdProductOptionValue(1),
                ],
            ]
        );

        $changeTransfer->getItems()[0]->setGroupKey('SKU');

        $groupKeyExpander = new GroupKeyExpander();
        $groupKeyExpander->expand($changeTransfer);

        $cartItem = $changeTransfer->getItems()[0];
        $this->assertEquals('SKU-1-2', $cartItem->getGroupKey());
    }

    /**
     * @return void
     */
    public function testWithDifferentOptionsOrder()
    {
        $changeTransfer = $this->createCartChangeTransfer(
            [
                'cartItem1' => [
                   (new ProductOptionTransfer())->setIdProductOptionValue(1),
                   (new ProductOptionTransfer())->setIdProductOptionValue(3),
                   (new ProductOptionTransfer())->setIdProductOptionValue('A'),
                   (new ProductOptionTransfer())->setIdProductOptionValue(2),
                ],
                'cartItem2' => [
                    (new ProductOptionTransfer())->setIdProductOptionValue(2),
                    (new ProductOptionTransfer())->setIdProductOptionValue(1),
                    (new ProductOptionTransfer())->setIdProductOptionValue(3),
                    (new ProductOptionTransfer())->setIdProductOptionValue('A'),
                ],
            ]
        );

        $groupKeyExpander = new GroupKeyExpander();
        $groupKeyExpander->expand($changeTransfer);

        $cartItem = $changeTransfer->getItems()[0];
        $this->assertEquals('A-1-2-3', $cartItem->getGroupKey());

        $cartItem = $changeTransfer->getItems()[1];
        $this->assertEquals('A-1-2-3', $cartItem->getGroupKey());
    }

    /**
     * @param array $cartItems
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(array $cartItems)
    {
        $changeTransfer = new CartChangeTransfer();

        foreach ($cartItems as $cartItem => $options) {
            $cartItem = new ItemTransfer();
            foreach ($options as $option) {
                $cartItem->addProductOption($option);
            }
            $changeTransfer->addItem($cartItem);
        }

        return $changeTransfer;
    }
}
