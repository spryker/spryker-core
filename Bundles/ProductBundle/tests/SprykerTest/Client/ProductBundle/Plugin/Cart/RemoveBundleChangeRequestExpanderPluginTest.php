<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductBundle\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\ProductBundle\Plugin\Cart\RemoveBundleChangeRequestExpanderPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ProductBundle
 * @group Plugin
 * @group Cart
 * @group RemoveBundleChangeRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class RemoveBundleChangeRequestExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductBundle\ProductBundleClientTester
     *
     */
    protected $tester;

    /**
     * @dataProvider expandRequestWithOneItemByAddTwoBundledProductsDataProvider
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param int $expectedResult
     *
     * @return void
     */
    public function testExpandRequestWithOneItemByAddBundleWithTwoItemsShouldReturnItemAndAllBundleItems(
        CartChangeTransfer $cartChangeTransfer,
        int $expectedResult
    ): void {
        //Act
        $removeBundleChangeRequestExpanderPlugin = $this->createRemoveBundleChangeRequestExpanderPlugin();
        $expendedChangeTransfer = $removeBundleChangeRequestExpanderPlugin->expand($cartChangeTransfer);

        //Assert
        $this->assertCount($expectedResult, $expendedChangeTransfer->getItems());
    }

    /**
     * @return array
     */
    public function expandRequestWithOneItemByAddTwoBundledProductsDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForExpandRequestWithOneItemByAddTwoBundledProductsDataProvider(2, 5, 3),
            'float stock' => $this->getDataForExpandRequestWithOneItemByAddTwoBundledProductsDataProvider(0.02, 0.05, 3),
        ];
    }

    /**
     * @param int|float $itemQty
     * @param int|float $bundleItemQty
     * @param int $expectedResult
     *
     * @return array
     */
    protected function getDataForExpandRequestWithOneItemByAddTwoBundledProductsDataProvider($itemQty, $bundleItemQty, int $expectedResult): array
    {
        $bundleGroupKey = 'group-1';
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => '666_666',
                ItemTransfer::QUANTITY => $itemQty,
            ])
            ->withItem([
                ItemTransfer::SKU => '111_111',
                ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => 'bundle-1-item-1',
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->withItem([
                ItemTransfer::SKU => '222_222',
                ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => 'bundle-1-item-2',
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->withBundleItem([
                ItemTransfer::SKU => '111_111',
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => 'bundle-1-item-1',
                ItemTransfer::GROUP_KEY => $bundleGroupKey,
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->withBundleItem([
                ItemTransfer::SKU => '222_222',
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => 'bundle-1-item-2',
                ItemTransfer::GROUP_KEY => $bundleGroupKey,
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->build();

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withItem([
                ItemTransfer::GROUP_KEY => $bundleGroupKey,
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->build();
        $cartChangeTransfer->addItem($quoteTransfer->getItems()[2]);
        $cartChangeTransfer->setQuote($quoteTransfer);

        return [$cartChangeTransfer, $expectedResult];
    }

    /**
     * @dataProvider expandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param int $expectedResult
     *
     * @return void
     */
    public function testExpandRequestWithNoItemsByAddBundleWithTwoItems(
        CartChangeTransfer $cartChangeTransfer,
        int $expectedResult
    ): void {
        //Act
        $removeBundleChangeRequestExpanderPlugin = $this->createRemoveBundleChangeRequestExpanderPlugin();
        $expendedChangeTransfer = $removeBundleChangeRequestExpanderPlugin->expand($cartChangeTransfer);

        //Assert
        $this->assertCount($expectedResult, $expendedChangeTransfer->getItems());
    }

    /**
     * @return array
     */
    public function expandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForExpandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider(3, 2),
            'float stock' => $this->getDataForExpandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider(0.03, 2),
        ];
    }

    /**
     * @param int|float $bundleItemQty
     * @param int $expectedResult
     *
     * @return array
     */
    protected function getDataForExpandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider($bundleItemQty, int $expectedResult): array
    {
        $bundleGroupKey = 'group-1';

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => '111_111',
                ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => 'bundle-1-item-1',
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->withItem([
                ItemTransfer::SKU => '222_222',
                ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => 'bundle-1-item-2',
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->withBundleItem([
                ItemTransfer::SKU => '111_111',
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => 'bundle-1-item-1',
                ItemTransfer::GROUP_KEY => $bundleGroupKey,
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->withBundleItem([
                ItemTransfer::SKU => '222_222',
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => 'bundle-1-item-2',
                ItemTransfer::GROUP_KEY => $bundleGroupKey,
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->build();

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withItem([
                ItemTransfer::GROUP_KEY => $bundleGroupKey,
                ItemTransfer::QUANTITY => $bundleItemQty,
            ])
            ->build();
        $cartChangeTransfer->setQuote($quoteTransfer);

        return [$cartChangeTransfer, $expectedResult];
    }

    /**
     * @return \Spryker\Client\ProductBundle\Plugin\Cart\RemoveBundleChangeRequestExpanderPlugin
     */
    protected function createRemoveBundleChangeRequestExpanderPlugin(): RemoveBundleChangeRequestExpanderPlugin
    {
        return new RemoveBundleChangeRequestExpanderPlugin();
    }
}
