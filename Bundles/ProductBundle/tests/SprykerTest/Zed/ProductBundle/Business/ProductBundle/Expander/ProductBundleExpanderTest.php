<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\ProductBundle\Expander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Expander\ProductBundleExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundle
 * @group Expander
 * @group ProductBundleExpanderTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester $tester
 */
class ProductBundleExpanderTest extends Unit
{
    /**
     * @var string
     */
    protected const BUNDLE_ITEM_IDENTIFIER = 'BUNDLE_ITEM_IDENTIFIER';

    /**
     * @var int
     */
    protected const BUNDLE_ITEM_QUANTITY = 1;

    /**
     * @var int
     */
    protected const BUNDLE_ITEM_SUM_PRICE = 100;

    /**
     * @var string
     */
    protected const ANOTHER_BUNDLE_ITEM_IDENTIFIER = 'ANOTHER_BUNDLE_ITEM_IDENTIFIER';

    /**
     * @var int
     */
    protected const ANOTHER_BUNDLE_ITEM_QUANTITY = 2;

    /**
     * @var int
     */
    protected const ANOTHER_BUNDLE_ITEM_SUM_PRICE = 300;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Expander\ProductBundleExpander
     */
    protected $productBundleExpander;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->productBundleExpander = new ProductBundleExpander();
    }

    /**
     * @return void
     */
    public function testNonUniqueBundleItemsQuantityAndPriceAreSummedUp(): void
    {
        // Arrange
        $orderTransfer = $this->tester->buildOrderTransferWithBundleItems([
            [
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::BUNDLE_ITEM_IDENTIFIER,
                ItemTransfer::QUANTITY => static::BUNDLE_ITEM_QUANTITY,
                ItemTransfer::SUM_PRICE => static::BUNDLE_ITEM_SUM_PRICE,
            ],
            [
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::BUNDLE_ITEM_IDENTIFIER,
                ItemTransfer::QUANTITY => static::ANOTHER_BUNDLE_ITEM_QUANTITY,
                ItemTransfer::SUM_PRICE => static::ANOTHER_BUNDLE_ITEM_SUM_PRICE,
            ],
        ]);
        $expectedBundleItemsCount = 1;
        $expectedFirstBundleItemQuantity = static::BUNDLE_ITEM_QUANTITY + static::ANOTHER_BUNDLE_ITEM_QUANTITY;
        $expectedFirstBundleItemSumPrice = static::BUNDLE_ITEM_SUM_PRICE + static::ANOTHER_BUNDLE_ITEM_SUM_PRICE;

        // Act
        $result = $this->productBundleExpander->expandUniqueOrderItemsWithProductBundles([], $orderTransfer);

        // Assert
        $this->assertCount($expectedBundleItemsCount, $result);
        $this->assertEquals($expectedFirstBundleItemQuantity, $result[0]->getQuantity());
        $this->assertEquals($expectedFirstBundleItemSumPrice, $result[0]->getSumPrice());
    }

    /**
     * @return void
     */
    public function testUniqueBundleItemsQuantityAndPriceAreNotSummedUp(): void
    {
        // Arrange
        $orderTransfer = $this->tester->buildOrderTransferWithBundleItems([
            [
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::BUNDLE_ITEM_IDENTIFIER,
                ItemTransfer::QUANTITY => static::BUNDLE_ITEM_QUANTITY,
                ItemTransfer::SUM_PRICE => static::BUNDLE_ITEM_SUM_PRICE,
            ],
            [
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::ANOTHER_BUNDLE_ITEM_IDENTIFIER,
                ItemTransfer::QUANTITY => static::ANOTHER_BUNDLE_ITEM_QUANTITY,
                ItemTransfer::SUM_PRICE => static::ANOTHER_BUNDLE_ITEM_SUM_PRICE,
            ],
        ]);
        $expectedBundleItemsCount = 2;
        $expectedFirstBundleItemQuantity = static::BUNDLE_ITEM_QUANTITY;
        $expectedFirstBundleItemSumPrice = static::BUNDLE_ITEM_SUM_PRICE;

        // Act
        $result = $this->productBundleExpander->expandUniqueOrderItemsWithProductBundles([], $orderTransfer);

        // Assert
        $this->assertCount($expectedBundleItemsCount, $result);
        $this->assertEquals($expectedFirstBundleItemQuantity, $result[0]->getQuantity());
        $this->assertEquals($expectedFirstBundleItemSumPrice, $result[0]->getSumPrice());
    }
}
