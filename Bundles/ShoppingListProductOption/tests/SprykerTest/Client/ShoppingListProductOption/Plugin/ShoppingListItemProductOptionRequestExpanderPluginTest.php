<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingListProductOption\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ShoppingListProductOption\Plugin\ShoppingListItemProductOptionRequestExpanderPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ShoppingListProductOption
 * @group Plugin
 * @group ShoppingListItemProductOptionRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class ShoppingListItemProductOptionRequestExpanderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testItemExpandedWithIdProductOption(): void
    {
        // Prepare
        $shoppingListItemTransfer = new ShoppingListItemTransfer();
        $params = [
            'product-option' => [
                1,
                2,
            ],
        ];

        // Action
        $shoppingListItemRequestExpanderPlugin = new ShoppingListItemProductOptionRequestExpanderPlugin();
        $shoppingListItemRequestExpanderPlugin->expand($shoppingListItemTransfer, $params);

        // Assert
        $idProductOptions = [];
        $this->assertNotEmpty($shoppingListItemTransfer->getProductOptions());
        foreach ($shoppingListItemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->assertNotEmpty($productOptionTransfer->getIdProductOptionValue());
            $idProductOptions[] = $productOptionTransfer->getIdProductOptionValue();
        }
        $this->assertSame($idProductOptions, $params['product-option']);
    }

    /**
     * @return void
     */
    public function testItemExpandedWithIdProductOptionFailsWithMalformedParams(): void
    {
        // Prepare
        $shoppingListItemTransfer = new ShoppingListItemTransfer();
        $params = [
            'product-option' => 1,
        ];

        // Action
        $shoppingListItemRequestExpanderPlugin = new ShoppingListItemProductOptionRequestExpanderPlugin();
        $shoppingListItemRequestExpanderPlugin->expand($shoppingListItemTransfer, $params);

        // Assert
        $this->assertEmpty($shoppingListItemTransfer->getProductOptions());
    }
}
