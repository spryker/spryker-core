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
     * @return void
     */
    public function testExpandInternals(
        $oneItemInGroupKey,
        $twoItemsInGroupKey,
        $bundledItems,
        $items
    ): void {
        $quoteTransfer = $this->tester->haveQuote();
        $quoteTransfer->setBundleItems($bundledItems);
        $quoteTransfer->setItems($items);

        ////////////////////// Test case: Quote items has bundled product items after Expand //////////////////////

        $bundledProductTotalQuantity = $this->getBundledProductTotalQuantity($quoteTransfer, $twoItemsInGroupKey);
        $this->assertEquals(7, $bundledProductTotalQuantity);

        $bundledProductTotalQuantity = $this->getBundledProductTotalQuantity($quoteTransfer, $oneItemInGroupKey);
        $this->assertEquals(1, $bundledProductTotalQuantity);

        $bundledProductTotalQuantity = $this->getBundledProductTotalQuantity($quoteTransfer, null);
        $this->assertEquals(0, $bundledProductTotalQuantity);

        ////////////////////// Test case: Remove one product bundle and get appropriate bundled items //////////////////////

        $returnedBundledItems = $this->getBundledItems($quoteTransfer, $oneItemInGroupKey, 1);
        $this->assertCount(1, $returnedBundledItems);

        $returnedBundledItems = $this->getBundledItems($quoteTransfer, $twoItemsInGroupKey, 1);
        $this->assertCount(2, $returnedBundledItems);
    }

    public function expandInternalsDataProvider(): array
    {
        return [
            'int-product-quantity' => $this->getDataForExpandInternalsDataProvider(),
        ];
    }

    protected function getDataForExpandInternalsDataProvider(): array
    {
        $twoItemsInGroupKey = 'group-1';
        $oneItemInGroupKey = 'group-2';
        $relatedBundleItemIdentifier1 = 'related-bundle-1';
        $relatedBundleItemIdentifier2 = 'related-bundle-2';

        $bundleItemTransfer1 = new ItemTransfer();
        $bundleItemTransfer1
            ->setSku('111_111')
            ->setGroupKey($twoItemsInGroupKey)
            ->setBundleItemIdentifier($relatedBundleItemIdentifier1)
            ->setQuantity(2);

        $bundleItemTransfer2 = new ItemTransfer();
        $bundleItemTransfer2
            ->setSku('222_222')
            ->setGroupKey($twoItemsInGroupKey)
            ->setBundleItemIdentifier($relatedBundleItemIdentifier1)
            ->setQuantity(5);

        $bundleItemTransfer3 = new ItemTransfer();
        $bundleItemTransfer3
            ->setSku('210_123')
            ->setGroupKey($oneItemInGroupKey)
            ->setBundleItemIdentifier($relatedBundleItemIdentifier2)
            ->setQuantity(1);

        $bundledItems = new ArrayObject([
            $bundleItemTransfer1,
            $bundleItemTransfer2,
            $bundleItemTransfer3,
        ]);

        $itemTransfer1 = new ItemTransfer();
        $itemTransfer1
            ->setSku('111_111')
            ->setGroupKey($twoItemsInGroupKey)
            ->setRelatedBundleItemIdentifier($relatedBundleItemIdentifier1)
            ->setQuantity(2);

        $itemTransfer2 = new ItemTransfer();
        $itemTransfer2
            ->setSku('222_222')
            ->setGroupKey($twoItemsInGroupKey)
            ->setRelatedBundleItemIdentifier($relatedBundleItemIdentifier1)
            ->setQuantity(5);

        $itemTransfer3 = new ItemTransfer();
        $itemTransfer3
            ->setSku('210_123')
            ->setGroupKey($oneItemInGroupKey)
            ->setRelatedBundleItemIdentifier($relatedBundleItemIdentifier2)
            ->setQuantity(1);

        $items = new ArrayObject([
            $itemTransfer1,
            $itemTransfer2,
            $itemTransfer3,
        ]);

        return [
            $oneItemInGroupKey,
            $twoItemsInGroupKey,
            $bundledItems,
            $items
        ];
    }

    /**
     * @dataProvider expandDataProvider
     *
     * @return void
     */
    public function testExpand(
        $oneItemInGroupKey,
        $twoItemsInGroupKey,
        $bundledItems,
        $items
    ): void {
        $quoteTransfer = $this->tester->haveQuote();
        $quoteTransfer->setBundleItems($bundledItems);
        $quoteTransfer->setItems($items);

        $cartChangeTransfer = $this->tester->haveCartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);
        $cartChangeTransfer->setItems(new ArrayObject());

        ////////////////////// Test case: Remove all items //////////////////////

        $expendedChangeTransfer = $this->expand($cartChangeTransfer, []);
        $this->assertCount(0, $expendedChangeTransfer->getItems());

        ////////////////////// Test case: Get all all //////////////////////

        $cartChangeTransfer->setItems($items);
        $expendedChangeTransfer = $this->expand($cartChangeTransfer, []);
        $this->assertCount(4, $expendedChangeTransfer->getItems());

        ////////////////////// Test case: Remove one product bundle and get appropriate items items //////////////////////
    }

    public function expandDataProvider(): array
    {
        return [
            'int-product-quantity' => $this->getDataForExpandDataProvider(),
        ];
    }

    protected function getDataForExpandDataProvider(): array
    {
        $twoItemsInGroupKey = 'group-1';
        $oneItemInGroupKey = 'group-2';
        $relatedBundleItemIdentifier1 = 'related-bundle-1';
        $relatedBundleItemIdentifier2 = 'related-bundle-2';

        $bundleItemTransfer1 = new ItemTransfer();
        $bundleItemTransfer1
            ->setSku('111_111')
            ->setGroupKey($twoItemsInGroupKey)
            ->setBundleItemIdentifier($relatedBundleItemIdentifier1)
            ->setQuantity(2);

        $bundleItemTransfer2 = new ItemTransfer();
        $bundleItemTransfer2
            ->setSku('222_222')
            ->setGroupKey($twoItemsInGroupKey)
            ->setBundleItemIdentifier($relatedBundleItemIdentifier1)
            ->setQuantity(5);

        $bundleItemTransfer3 = new ItemTransfer();
        $bundleItemTransfer3
            ->setSku('210_123')
            ->setGroupKey($oneItemInGroupKey)
            ->setBundleItemIdentifier($relatedBundleItemIdentifier2)
            ->setQuantity(1);

        $bundleItemTransfer4 = new ItemTransfer();
        $bundleItemTransfer4
            ->setSku('666_666')
            ->setQuantity(6);

        $bundledItems = new ArrayObject([
            $bundleItemTransfer1,
            $bundleItemTransfer2,
            $bundleItemTransfer3,
            $bundleItemTransfer4,
        ]);

        $itemTransfer1 = new ItemTransfer();
        $itemTransfer1
            ->setSku('111_111')
            ->setGroupKey($twoItemsInGroupKey)
            ->setRelatedBundleItemIdentifier($relatedBundleItemIdentifier1)
            ->setQuantity(2);

        $itemTransfer2 = new ItemTransfer();
        $itemTransfer2
            ->setSku('222_222')
            ->setGroupKey($twoItemsInGroupKey)
            ->setRelatedBundleItemIdentifier($relatedBundleItemIdentifier1)
            ->setQuantity(5);

        $itemTransfer3 = new ItemTransfer();
        $itemTransfer3
            ->setSku('210_123')
            ->setGroupKey($oneItemInGroupKey)
            ->setRelatedBundleItemIdentifier($relatedBundleItemIdentifier2)
            ->setQuantity(1);

        $itemTransfer4 = new ItemTransfer();
        $itemTransfer4
            ->setSku('666_666')
            ->setQuantity(6);

        $items = new ArrayObject([
            $itemTransfer1,
            $itemTransfer2,
            $itemTransfer3,
            $itemTransfer4,
        ]);

        return [
            $oneItemInGroupKey,
            $twoItemsInGroupKey,
            $bundledItems,
            $items
        ];
    }

    //////////////////////////

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
