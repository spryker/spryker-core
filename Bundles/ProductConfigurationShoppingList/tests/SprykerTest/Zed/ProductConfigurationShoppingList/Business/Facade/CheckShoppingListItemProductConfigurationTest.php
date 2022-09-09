<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationShoppingList\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use SprykerTest\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationShoppingList
 * @group Business
 * @group Facade
 * @group CheckShoppingListItemProductConfigurationTest
 * Add your own group annotations below this line
 */
class CheckShoppingListItemProductConfigurationTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListBusinessTester
     */
    protected ProductConfigurationShoppingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testCheckShoppingListItemProductConfigurationExists(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($productConcreteTransfer);

        $this->tester->haveProductConfiguration(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ],
        );

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkShoppingListItemProductConfiguration($shoppingListItemTransfer);

        // Assert
        $this->assertTrue($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckShoppingListItemProductConfigurationNotExists(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($productConcreteTransfer);

        $shoppingListItemTransfer->setProductConfigurationInstance(
            new ProductConfigurationInstanceTransfer(),
        );

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkShoppingListItemProductConfiguration($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
    }
}
