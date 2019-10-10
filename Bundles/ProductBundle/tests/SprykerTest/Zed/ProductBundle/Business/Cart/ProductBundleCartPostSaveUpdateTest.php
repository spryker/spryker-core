<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartPostSaveUpdate;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Cart
 * @group ProductBundleCartPostSaveUpdateTest
 * Add your own group annotations below this line
 */
class ProductBundleCartPostSaveUpdateTest extends Unit
{
    /**
     * @return void
     */
    public function testUpdateBundlesShouldRemoveStaleBundles()
    {
        $productBundlesCartPostSaveUpdate = new ProductBundleCartPostSaveUpdate();

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setRelatedBundleItemIdentifier('related-bundle-identifier');
        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setBundleItemIdentifier('related-bundle-identifier');
        $quoteTransfer->addBundleItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setBundleItemIdentifier('not-existing');
        $quoteTransfer->addBundleItem($itemTransfer);

        $updatedQuoteTransfer = $productBundlesCartPostSaveUpdate->updateBundles($quoteTransfer);

        $this->assertCount(1, $updatedQuoteTransfer->getBundleItems());
    }
}
