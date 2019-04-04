<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductBundle\Plugin\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductBundle\Plugin\Cart\RemoveBundleChangeRequestExpanderPlugin;
use Spryker\Client\ProductBundle\QuoteChangeRequestExpander\QuoteChangeRequestExpander;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ProductBundle
 * @group Plugin
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $cartChangeTransfer
     *
     * @return void
     */
    public function testExpandRequestWithOneItemByAddBundleWithTwoItems($cartChangeTransfer): void
    {
        //Act
        $removeBundleChangeRequestExpanderPlugin = $this->createRemoveBundleChangeRequestExpanderPlugin();
        $expendedChangeTransfer = $removeBundleChangeRequestExpanderPlugin->expand($cartChangeTransfer);

        //Assert
        $this->assertCount(3, $expendedChangeTransfer->getItems());
    }

    /**
     * @dataProvider expandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $cartChangeTransfer
     *
     * @return void
     */
    public function testExpandRequestWithNoItemsByAddBundleWithTwoItems($cartChangeTransfer): void
    {
        //Act
        $removeBundleChangeRequestExpanderPlugin = $this->createRemoveBundleChangeRequestExpanderPlugin();
        $expendedChangeTransfer = $removeBundleChangeRequestExpanderPlugin->expand($cartChangeTransfer);

        //Assert
        $this->assertCount(2, $expendedChangeTransfer->getItems());
    }

    /**
     * @return array
     */
    public function expandInternalsDataProvider(): array
    {
        return [
            'int-product-quantity' => $this->getDataForExpandInternalsDataProvider(),
        ];
    }

    /**
     * @return array
     */
    public function expandRequestWithOneItemByAddTwoBundledProductsDataProvider(): array
    {
        return [
            'int-product-quantity' => $this->getDataForExpandRequestWithOneItemByAddTwoBundledProductsDataProvider(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForExpandRequestWithOneItemByAddTwoBundledProductsDataProvider(): array
    {
        $bundleGroupKey = 'group-1';

        $bundleTransfer1 = (new ItemTransfer())
            ->setGroupKey($bundleGroupKey)
            ->setQuantity(2);

        $bundleItemTransfer1 = (new ItemTransfer())
            ->setSku('111_111')
            ->setBundleItemIdentifier('bundle-1-item-1')
            ->setGroupKey($bundleGroupKey)
            ->setQuantity(2);

        $bundleItemTransfer2 = (new ItemTransfer())
            ->setSku('222_222')
            ->setBundleItemIdentifier('bundle-1-item-2')
            ->setGroupKey($bundleGroupKey)
            ->setQuantity(5);

        $bundledItems = new ArrayObject([
            $bundleItemTransfer1,
            $bundleItemTransfer2,
        ]);

        $itemTransfer1 = (new ItemTransfer())
            ->setSku('111_111')
            ->setRelatedBundleItemIdentifier('bundle-1-item-1')
            ->setQuantity(2);

        $itemTransfer2 = (new ItemTransfer())
            ->setSku('222_222')
            ->setRelatedBundleItemIdentifier('bundle-1-item-2')
            ->setQuantity(5);

        $itemTransfer3 = new ItemTransfer();
        $itemTransfer3
            ->setSku('666_666')
            ->setQuantity(6);

        $items = new ArrayObject([
            $itemTransfer1,
            $itemTransfer2,
            $itemTransfer3,
        ]);

        $changeItems = new ArrayObject([
            $bundleTransfer1,
            $itemTransfer3,
        ]);

        $quoteTransfer = $this->tester->haveQuote();
        $quoteTransfer->setBundleItems($bundledItems);
        $quoteTransfer->setItems($items);

        $cartChangeTransfer = $this->tester->haveCartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);
        $cartChangeTransfer->setItems($changeItems);

        return [$cartChangeTransfer];
    }

    /**
     * @return array
     */
    public function expandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider(): array
    {
        return [
            'int-product-quantity' => $this->getDataForExpandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForExpandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider(): array
    {
        $bundleGroupKey = 'group-1';

        $bundleTransfer1 = (new ItemTransfer())
            ->setGroupKey($bundleGroupKey)
            ->setQuantity(2);

        $bundleItemTransfer1 = (new ItemTransfer())
            ->setSku('111_111')
            ->setBundleItemIdentifier('bundle-1-item-1')
            ->setGroupKey($bundleGroupKey)
            ->setQuantity(2);

        $bundleItemTransfer2 = (new ItemTransfer())
            ->setSku('222_222')
            ->setBundleItemIdentifier('bundle-1-item-2')
            ->setGroupKey($bundleGroupKey)
            ->setQuantity(5);

        $bundledItems = new ArrayObject([
            $bundleItemTransfer1,
            $bundleItemTransfer2,
        ]);

        $itemTransfer1 = (new ItemTransfer())
            ->setSku('111_111')
            ->setRelatedBundleItemIdentifier('bundle-1-item-1')
            ->setQuantity(2);

        $itemTransfer2 = (new ItemTransfer())
            ->setSku('222_222')
            ->setRelatedBundleItemIdentifier('bundle-1-item-2')
            ->setQuantity(5);

        $items = new ArrayObject([
            $itemTransfer1,
            $itemTransfer2,
        ]);

        $changeItems = new ArrayObject([
            $bundleTransfer1,
        ]);

        $quoteTransfer = $this->tester->haveQuote();
        $quoteTransfer->setBundleItems($bundledItems);
        $quoteTransfer->setItems($items);

        $cartChangeTransfer = $this->tester->haveCartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);
        $cartChangeTransfer->setItems($changeItems);

        return [$cartChangeTransfer];
    }

    /**
     * @return \Spryker\Client\ProductBundle\Plugin\Cart\RemoveBundleChangeRequestExpanderPlugin
     */
    protected function createRemoveBundleChangeRequestExpanderPlugin(): RemoveBundleChangeRequestExpanderPlugin
    {
        return new RemoveBundleChangeRequestExpanderPlugin();
    }
}
