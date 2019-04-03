<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductBundle\QuoteChangeRequestExpander;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductBundle\QuoteChangeRequestExpander\QuoteChangeRequestExpander;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ProductBundle
 * @group QuoteChangeRequestExpander
 * @group QuoteChangeRequestExpanderTest
 * Add your own group annotations below this line
 */
class QuoteChangeRequestExpanderTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductBundle\ProductBundleClientTester
     */
    protected $tester;

    /**
     * @dataProvider expandInternalsDataProvider
     *
     * @group currentwork
     *
     * @return void
     */
    public function testExpandInternals(
        $oneItemInGroupKey,
        $threeItemsInGroupKey,
        $bundledItems,
        $items,
        $bundledItemsAfterRemove,
        $itemsAfterRemove
    ): void {
        $quoteTransfer = $this->tester->haveQuote();
        $quoteTransfer->setBundleItems($bundledItems);
        $quoteTransfer->setItems($items);

        ////////////////////// Returns quantity sum for all items in a bundle //////////////////////

        $bundledProductTotalQuantity = $this->getBundledProductTotalQuantity($quoteTransfer, $threeItemsInGroupKey);
        $this->assertEquals(7, $bundledProductTotalQuantity);

        $bundledProductTotalQuantity = $this->getBundledProductTotalQuantity($quoteTransfer, $oneItemInGroupKey);
        $this->assertEquals(4, $bundledProductTotalQuantity);

        $bundledProductTotalQuantity = $this->getBundledProductTotalQuantity($quoteTransfer, null);
        $this->assertEquals(0, $bundledProductTotalQuantity);

        ////////////////////// No changes in cart, getBundledItems returns the same count /////////////////////

        $howManyShouldStayAfterExpand = 1;
        $returnedBundledItems = $this->getBundledItems($quoteTransfer, $oneItemInGroupKey, null);
        $this->assertCount($howManyShouldStayAfterExpand, $returnedBundledItems);

        $howManyShouldStayAfterExpand = 3;
        $returnedBundledItems = $this->getBundledItems($quoteTransfer, $threeItemsInGroupKey, null);
        $this->assertCount($howManyShouldStayAfterExpand, $returnedBundledItems);

        ////////////////////// Remove some items from cart, getBundledItems returns the same count /////////////////////
        $quoteTransfer = $this->tester->haveQuote();
        $quoteTransfer->setBundleItems($bundledItemsAfterRemove);
        $quoteTransfer->setItems($itemsAfterRemove);

        $howManyShouldStayAfterExpand = 0;
        $returnedBundledItems = $this->getBundledItems($quoteTransfer, $oneItemInGroupKey, null);
        $this->assertCount($howManyShouldStayAfterExpand, $returnedBundledItems);

        $howManyShouldStayAfterExpand = 2;
        $returnedBundledItems = $this->getBundledItems($quoteTransfer, $threeItemsInGroupKey, null);
        $this->assertCount($howManyShouldStayAfterExpand, $returnedBundledItems);
    }

    /**
     * @dataProvider expandRequestWithOneItemByAddTwoBundledProductsDataProvider
     *
     *
     * @return void
     */
    public function testExpandRequestWithOneItemByAddBundleWithTwoItems(
        $quoteBundleItems,
        $quoteItems,
        $oneProductPlusOneBundleChangeItems
    ): void {
        $quoteTransfer = $this->tester->haveQuote();
        $quoteTransfer->setBundleItems($quoteBundleItems);
        $quoteTransfer->setItems($quoteItems);

        $cartChangeTransfer = $this->tester->haveCartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);
        $cartChangeTransfer->setItems($oneProductPlusOneBundleChangeItems);

        $expendedChangeTransfer = $this->expand($cartChangeTransfer);

        $this->assertCount(3, $expendedChangeTransfer->getItems());
    }

    /**
     * @dataProvider expandRequestWithNoItemsByAddBundleWithTwoItemsDataProvider
     *
     * @return void
     */
    public function testExpandRequestWithNoItemsByAddBundleWithTwoItems(
        $quoteBundleItems,
        $quoteItems,
        $oneBundleChangeItems
    ): void {
        $quoteTransfer = $this->tester->haveQuote();
        $quoteTransfer->setBundleItems($quoteBundleItems);
        $quoteTransfer->setItems($quoteItems);

        $cartChangeTransfer = $this->tester->haveCartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);
        $cartChangeTransfer->setItems($oneBundleChangeItems);

        $expendedChangeTransfer = $this->expand($cartChangeTransfer);

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
    protected function getDataForExpandInternalsDataProvider(): array
    {
        $threeItemsBundleGroupKey = 'group-1';

        $bundleItemTransfer1 = (new ItemTransfer())
            ->setSku('111_111')
            ->setBundleItemIdentifier('bundle-1-item-1')
            ->setGroupKey($threeItemsBundleGroupKey)
            ->setQuantity(2);

        $bundleItemTransfer2 = (new ItemTransfer())
            ->setSku('222_222')
            ->setBundleItemIdentifier('bundle-1-item-2')
            ->setGroupKey($threeItemsBundleGroupKey)
            ->setQuantity(3);

        $bundleItemTransfer21 = (new ItemTransfer())
            ->setSku('222_111')
            ->setBundleItemIdentifier('bundle-1-item-3')
            ->setGroupKey($threeItemsBundleGroupKey)
            ->setQuantity(2);

        $oneItemBundleGroupKey = 'group-2';
        $bundleItemTransfer3 = (new ItemTransfer())
            ->setSku('333_333')
            ->setBundleItemIdentifier('bundle-2-item-1')
            ->setGroupKey($oneItemBundleGroupKey)
            ->setQuantity(4);

        $bundledItems = new ArrayObject([
            $bundleItemTransfer1,
            $bundleItemTransfer2,
            $bundleItemTransfer21,
            $bundleItemTransfer3
        ]);

        $itemTransfer1 = (new ItemTransfer())
            ->setSku('111_111')
            ->setRelatedBundleItemIdentifier('bundle-1-item-1')
            ->setQuantity(2);

        $itemTransfer2 = (new ItemTransfer())
            ->setSku('222_222')
            ->setRelatedBundleItemIdentifier('bundle-1-item-2')
            ->setQuantity(3);

        $itemTransfer21 = (new ItemTransfer())
            ->setSku('222_111')
            ->setRelatedBundleItemIdentifier('bundle-1-item-3')
            ->setQuantity(2);

        $itemTransfer3 = (new ItemTransfer())
            ->setSku('333_333')
            ->setRelatedBundleItemIdentifier('bundle-2-item-1')
            ->setQuantity(4);

        $itemTransfer4 = new ItemTransfer();
        $itemTransfer4
            ->setSku('666_666')
            ->setQuantity(6);

        $items = new ArrayObject([
            $itemTransfer1,
            $itemTransfer2,
            $itemTransfer21,
            $itemTransfer3,
            $itemTransfer4,
        ]);

        $bundledItemsAfterRemove = new ArrayObject([
            $bundleItemTransfer1,
            $bundleItemTransfer2,
        ]);

        $itemsAfterRemove = new ArrayObject([
            $itemTransfer1,
            $itemTransfer2,
            $itemTransfer3,
            $itemTransfer4,
        ]);

        return [
            $oneItemBundleGroupKey,
            $threeItemsBundleGroupKey,
            $bundledItems,
            $items,
            $bundledItemsAfterRemove,
            $itemsAfterRemove
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

        return [
            $bundledItems,
            $items,
            $changeItems
        ];
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

        return [
            $bundledItems,
            $items,
            $changeItems
        ];
    }

    /////////////// Hardcoded methods from Spryker\Client\ProductBundle\QuoteChangeRequestExpander\QuoteChangeRequestExpander ////////

    protected function expand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        $itemTransferList = [];
        foreach ($cartChangeTransfer->getItems() as $quoteTransfer) {
            $bundledItemTransferList = $this->getBundledItems($cartChangeTransfer->getQuote(), $quoteTransfer->getGroupKey(), $quoteTransfer->getQuantity());
            if (count($bundledItemTransferList)) {
                $itemTransferList = array_merge($itemTransferList, $bundledItemTransferList);
                continue;
            }
            $itemTransferList[] = $quoteTransfer;
        }
        $cartChangeTransfer->setItems(new ArrayObject($itemTransferList));

        return $cartChangeTransfer;
    }

    protected function getBundledItems($quoteTransfer, $groupKey, $numberOfBundlesToRemove): array
    {
        if (!$numberOfBundlesToRemove) {
            $numberOfBundlesToRemove = $this->getBundledProductTotalQuantity($quoteTransfer, $groupKey);
        }
        $bundledItems = [];
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($numberOfBundlesToRemove === 0) {
                return $bundledItems;
            }

            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getRelatedBundleItemIdentifier() !== $bundleItemTransfer->getBundleItemIdentifier()) {
                    continue;
                }
                $bundledItems[] = $itemTransfer;
            }
            $numberOfBundlesToRemove--;
        }

        return $bundledItems;
    }

    protected function getBundledProductTotalQuantity(QuoteTransfer $quoteTransfer, $groupKey): int
    {
        $bundleItemQuantity = 0;
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }
            $bundleItemQuantity += $bundleItemTransfer->getQuantity();
        }

        return $bundleItemQuantity;
    }
}
