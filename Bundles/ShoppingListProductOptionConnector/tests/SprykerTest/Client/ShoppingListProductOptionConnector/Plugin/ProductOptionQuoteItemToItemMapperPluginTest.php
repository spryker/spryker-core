<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingListProductOptionConnector\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\QuoteItemToItemMapperPluginInterface;
use Spryker\Client\ShoppingListProductOptionConnector\ShoppingList\ProductOptionQuoteItemToItemMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ShoppingListProductOptionConnector
 * @group Plugin
 * @group ProductOptionQuoteItemToItemMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductOptionQuoteItemToItemMapperPluginTest extends Unit
{
    protected const GROUP_KEY = 'group_key';

    /**
     * @var \SprykerTest\Client\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapWithSameItemInCart(): void
    {
        // Prepare
        $itemTransfer = (new ItemTransfer())->setSku('sku_sample');
        $quoteItemTransfer = (new ItemTransfer())->setSku('sku_sample')
            ->setGroupKey(static::GROUP_KEY);

        // Action
        $productOptionQuoteItemToItemMapperPlugin = $this->createProductOptionQuoteItemToItemMapperPlugin();

        $actualResult = $productOptionQuoteItemToItemMapperPlugin->map(
            $quoteItemTransfer,
            $itemTransfer
        );

        // Assert
        $this->assertSame(static::GROUP_KEY, $actualResult->getGroupKey());
    }

    /**
     * @return void
     */
    public function testMapWithDifferentItemInCart(): void
    {
        // Prepare
        $itemTransfer = (new ItemTransfer())->setSku('sku_sample');

        $productOptionTransfer = (new ProductOptionTransfer())->setIdProductOptionValue(1);
        $quoteItemTransfer = (new ItemTransfer())->setSku('sku_sample')
            ->setGroupKey(static::GROUP_KEY)
            ->setProductOptions(new ArrayObject([$productOptionTransfer]));

        // Action
        $productOptionQuoteItemToItemMapperPlugin = $this->createProductOptionQuoteItemToItemMapperPlugin();

        $actualResult = $productOptionQuoteItemToItemMapperPlugin->map(
            $quoteItemTransfer,
            $itemTransfer
        );

        // Assert
        $this->assertEmpty($actualResult->getGroupKey());
    }

    /**
     * @return void
     */
    public function testMapWithSameItemWithOptionsInCart(): void
    {
        // Prepare
        $productOptionTransfer = (new ProductOptionTransfer())->setIdProductOptionValue(1);
        $itemTransfer = (new ItemTransfer())->setSku('sku_sample')
            ->setProductOptions(new ArrayObject([$productOptionTransfer]));
        $quoteItemTransfer = (new ItemTransfer())->setSku('sku_sample')
            ->setGroupKey(static::GROUP_KEY)
            ->setProductOptions(new ArrayObject([$productOptionTransfer]));

        // Action
        $productOptionQuoteItemToItemMapperPlugin = $this->createProductOptionQuoteItemToItemMapperPlugin();

        $actualResult = $productOptionQuoteItemToItemMapperPlugin->map(
            $quoteItemTransfer,
            $itemTransfer
        );

        // Assert
        $this->assertSame(static::GROUP_KEY, $actualResult->getGroupKey());
    }

    /**
     * @return \Spryker\Client\ShoppingListExtension\Dependency\Plugin\QuoteItemToItemMapperPluginInterface
     */
    protected function createProductOptionQuoteItemToItemMapperPlugin(): QuoteItemToItemMapperPluginInterface
    {
        return new ProductOptionQuoteItemToItemMapperPlugin();
    }
}
