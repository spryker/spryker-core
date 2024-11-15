<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundle\Communication\Plugin\Quote\SanitizeBundleItemsBeforeQuoteSavePlugin;
use SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group SanitizeBundleItemsBeforeQuoteSavePluginTest
 * Add your own group annotations below this line
 */
class SanitizeBundleItemsBeforeQuoteSavePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester
     */
    protected ProductBundleCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldSanitizeBundleItems(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->addBundleItem(new ItemTransfer())
            ->addBundleItem(new ItemTransfer());

        // Arrange
        $quoteTransfer = (new SanitizeBundleItemsBeforeQuoteSavePlugin())->execute($quoteTransfer);

        // Assert
        $this->assertEmpty($quoteTransfer->getBundleItems());
    }

    /**
     * @return void
     */
    public function testShouldNotSanitizeBundleItems(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->addBundleItem(new ItemTransfer())
            ->addBundleItem(new ItemTransfer())
            ->addItem(new ItemTransfer());

        // Arrange
        $quoteTransfer = (new SanitizeBundleItemsBeforeQuoteSavePlugin())->execute($quoteTransfer);

        // Assert
        $this->assertNotEmpty($quoteTransfer->getBundleItems());
    }
}
