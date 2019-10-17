<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingListProductOptionConnector\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ShoppingListProductOptionConnector\ShoppingList\ShoppingListItemProductOptionToItemProductOptionMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ShoppingListProductOptionConnector
 * @group Plugin
 * @group ShoppingListItemProductOptionToItemProductOptionMapperPluginTest
 * Add your own group annotations below this line
 */
class ShoppingListItemProductOptionToItemProductOptionMapperPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapShoppingListItemProductOptionToItemProductOptionWithItemNotInCart(): void
    {
        // Prepare
        $productTransfer = $this->tester->haveProduct();
        $productOptionGroupTransfer = $this->tester->createProductOptionGroupTransfer($productTransfer->getSku());
        $shoppingListItemTransfer = (new ShoppingListItemTransfer());
        foreach ($productOptionGroupTransfer->getProductOptionValues() as $productOptionValueTransfer) {
            $productOptionTransfer = (new ProductOptionTransfer())
                ->setGroupName($productOptionGroupTransfer->getName())
                ->setIdProductOptionValue($productOptionValueTransfer->getIdProductOptionValue())
                ->setValue($productOptionValueTransfer->getValue());

            $shoppingListItemTransfer->addProductOption($productOptionTransfer);
        }
        $itemTransfer = (new ItemTransfer())->setSku('sku_sample');

        // Action
        $shoppingListItemProductOptionMapperPlugin = $this->createShoppingListItemProductOptionToItemProductOptionMapperPlugin();
        $actualResult = $shoppingListItemProductOptionMapperPlugin->map(
            $shoppingListItemTransfer,
            $itemTransfer
        );

        // Assert
        $this->assertCount(1, $actualResult->getProductOptions());
        foreach ($actualResult->getProductOptions() as $productOptionTransfer) {
            $this->assertContains(
                $productOptionTransfer->getValue(),
                array_map(function (ProductOptionValueTransfer $productOptionValueTransfer) {
                    return $productOptionValueTransfer->getValue();
                }, $productOptionGroupTransfer->getProductOptionValues()->getArrayCopy())
            );
        }
    }

    /**
     * @return void
     */
    public function testMapShoppingListItemProductOptionToItemProductOptionWithItemInCart(): void
    {
        // Prepare
        $sku = 'sku_sample';
        $productTransfer = $this->tester->haveProduct();
        $productOptionGroupTransfer = $this->tester->createProductOptionGroupTransfer($productTransfer->getSku());
        $itemTransfer = (new ItemTransfer())->setSku($sku);
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())->setSku($sku);
        foreach ($productOptionGroupTransfer->getProductOptionValues() as $productOptionValueTransfer) {
            $productOptionTransfer = (new ProductOptionTransfer())
                ->setGroupName($productOptionGroupTransfer->getName())
                ->setValue($productOptionValueTransfer->getValue());

            $shoppingListItemTransfer->addProductOption($productOptionTransfer);
        }

        // Action
        $shoppingListItemProductOptionMapperPlugin = $this->createShoppingListItemProductOptionToItemProductOptionMapperPlugin();
        $actualResult = $shoppingListItemProductOptionMapperPlugin->map(
            $shoppingListItemTransfer,
            $itemTransfer
        );

        // Assert
        $this->assertCount(1, $actualResult->getProductOptions());
        foreach ($actualResult->getProductOptions() as $productOptionTransfer) {
            $this->assertContains(
                $productOptionTransfer->getValue(),
                array_map(function (ProductOptionValueTransfer $productOptionValueTransfer) {
                    return $productOptionValueTransfer->getValue();
                }, $productOptionGroupTransfer->getProductOptionValues()->getArrayCopy())
            );
        }
    }

    /**
     * @return \Spryker\Client\ShoppingListProductOptionConnector\ShoppingList\ShoppingListItemProductOptionToItemProductOptionMapperPlugin
     */
    protected function createShoppingListItemProductOptionToItemProductOptionMapperPlugin(): ShoppingListItemProductOptionToItemProductOptionMapperPlugin
    {
        return new ShoppingListItemProductOptionToItemProductOptionMapperPlugin();
    }
}
