<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartItemGroupKeyExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Cart
 * @group ProductBundleCartItemGroupKeyExpanderTest
 * Add your own group annotations below this line
 */
class ProductBundleCartItemGroupKeyExpanderTest extends Unit
{
    /**
     * @return void
     */
    public function testExpandBundleItemGroupKeyShouldIncludeBundledIdentifierIntoGroupKey()
    {
        $productBundleCartItemGroupKeyExpander = $this->createGroupKeyExpander();

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku('sku-123');
        $itemTransfer->setGroupKey('sku-123');
        $itemTransfer->setRelatedBundleItemIdentifier('related-bundle-id');

        $cartChangeTransfer->addItem($itemTransfer);

        $updatedCartChangeTransfer = $productBundleCartItemGroupKeyExpander->expandExpandBundleItemGroupKey($cartChangeTransfer);

        $updateBundledItemTransfer = $updatedCartChangeTransfer->getItems()[0];

        $this->assertContains($itemTransfer->getRelatedBundleItemIdentifier(), $updateBundledItemTransfer->getGroupKey());
    }

    /**
     * @return void
     */
    public function testExpandBundleGroupKeyShouldMakeBundledItemsWithSameSkuHaveUniqueGroupKey()
    {
        $productBundleCartItemGroupKeyExpander = $this->createGroupKeyExpander();

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku('sku-123');
        $itemTransfer->setGroupKey('sku-123');
        $itemTransfer->setRelatedBundleItemIdentifier('related-bundle-id');
        $cartChangeTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku('sku-123');
        $itemTransfer->setGroupKey('sku-123');
        $itemTransfer->setRelatedBundleItemIdentifier('related-bundle-id');
        $cartChangeTransfer->addItem($itemTransfer);

        $updatedCartChangeTransfer = $productBundleCartItemGroupKeyExpander->expandExpandBundleItemGroupKey($cartChangeTransfer);

        $updateBundledItemTransfer1 = $updatedCartChangeTransfer->getItems()[0];
        $updateBundledItemTransfer2 = $updatedCartChangeTransfer->getItems()[1];

        $this->assertNotEquals($updateBundledItemTransfer1->getGroupKey(), $updateBundledItemTransfer2->getGroupKey());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartItemGroupKeyExpander
     */
    protected function createGroupKeyExpander()
    {
        return new ProductBundleCartItemGroupKeyExpander();
    }
}
