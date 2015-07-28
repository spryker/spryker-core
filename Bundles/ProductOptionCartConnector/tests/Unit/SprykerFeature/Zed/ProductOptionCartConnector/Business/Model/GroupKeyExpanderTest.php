<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\ProductOptionCartConnector\Business\Model;

use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use SprykerFeature\Zed\ProductOptionCartConnector\Business\Model\GroupKeyExpander;
use Generated\Shared\Transfer\ChangeTransfer;

class GroupKeyExpanderTest extends \PHPUnit_Framework_TestCase
{
    public function testKeyGroupByProvidedOptions()
    {
        $changeTransfer = $this->createCartChangeTransfer(
            [
                'cartItem1' => [
                   (new ProductOptionTransfer())->setIdOptionValueUsage(2),
                   (new ProductOptionTransfer())->setIdOptionValueUsage(1),
                ]
            ]
        );

        $groupKeyExpander = new GroupKeyExpander();
        $groupKeyExpander->expand($changeTransfer);

        $cartItem = $changeTransfer->getItems()[0];
        $this->assertEquals('1-2', $cartItem->getGroupKey());
    }

    public function testWithExistingGroupKey()
    {
        $changeTransfer = $this->createCartChangeTransfer(
            [
                'cartItem1' => [
                   (new ProductOptionTransfer())->setIdOptionValueUsage(2),
                   (new ProductOptionTransfer())->setIdOptionValueUsage(1),
                ]
            ]
        );

        $changeTransfer->getItems()[0]->setGroupKey('SKU');

        $groupKeyExpander = new GroupKeyExpander();
        $groupKeyExpander->expand($changeTransfer);

        $cartItem = $changeTransfer->getItems()[0];
        $this->assertEquals('SKU-1-2', $cartItem->getGroupKey());
    }

    public function testWithDifferentOptionsOrder()
    {
        $changeTransfer = $this->createCartChangeTransfer(
            [
                'cartItem1' => [
                   (new ProductOptionTransfer())->setIdOptionValueUsage(1),
                   (new ProductOptionTransfer())->setIdOptionValueUsage(3),
                   (new ProductOptionTransfer())->setIdOptionValueUsage('A'),
                   (new ProductOptionTransfer())->setIdOptionValueUsage(2),
                ],
                'cartItem2' => [
                    (new ProductOptionTransfer())->setIdOptionValueUsage(2),
                    (new ProductOptionTransfer())->setIdOptionValueUsage(1),
                    (new ProductOptionTransfer())->setIdOptionValueUsage(3),
                    (new ProductOptionTransfer())->setIdOptionValueUsage('A'),
                ]
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
     * @return ChangeTransfer
     */
    protected function createCartChangeTransfer(array $cartItems)
    {
        $changeTransfer = new ChangeTransfer();

        foreach ($cartItems as $cartItem => $options) {
            $cartItem = new CartItemTransfer();
            foreach ($options as $option) {
                $cartItem->addProductOption($option);
            }
            $changeTransfer->addItem($cartItem);
        }


        return $changeTransfer;
    }
}
