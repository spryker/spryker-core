<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\ProductBundleFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundleFacade
 * @group RefreshBundlesWithUnitedItemsToBeInSyncWithQuoteTest
 * Add your own group annotations below this line
 */
class RefreshBundlesWithUnitedItemsToBeInSyncWithQuoteTest extends Unit
{
    /**
     * @var string
     */
    protected const EXISTING_BUNDLE_IDENTIFIER = 'bundle-identifier';

    /**
     * @var string
     */
    protected const NON_EXISTENT_BUNDLE_IDENTIFIER = 'non-existent-bundle-identifier';

    /**
     * @var int
     */
    protected const ACTUAL_ITEM_QUANTITY = 3;

    /**
     * @var int
     */
    protected const STALE_BUNDLE_QUANTITY = 4;

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBundlesUpToDateWhenRefreshed(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer($this->getQuoteTransferFixturesWithBundle());

        // Act
        $quoteTransfer = $this->tester->getFacade()->refreshBundlesWithUnitedItemsToBeInSyncWithQuote($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getBundleItems(), 'Bundle items should contain 1 element');

        $bundleItemTransfer = $quoteTransfer->getBundleItems()->offsetGet(0);
        $this->assertEquals(
            $bundleItemTransfer->getBundleItemIdentifier(),
            static::EXISTING_BUNDLE_IDENTIFIER,
            'Only the existing bundle should be kept in QuoteTransfer.bundleItems',
        );
        $this->assertEquals(
            $bundleItemTransfer->getQuantity(),
            static::ACTUAL_ITEM_QUANTITY,
            'Bundle quantity should be updated to the actual value',
        );
    }

    /**
     * @return void
     */
    public function testQuoteNotChangedWhenNoBundles(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer($this->getQuoteTransferFixturesWithoutBundle());
        $initialQuoteTransfer = (new QuoteTransfer())->fromArray($quoteTransfer->toArray());

        // Act
        $quoteTransfer = $this->tester->getFacade()->refreshBundlesWithUnitedItemsToBeInSyncWithQuote($quoteTransfer);

        // Assert
        $this->assertEquals(
            $initialQuoteTransfer->toArray(),
            $quoteTransfer->toArray(),
            'Quote transfer should not be changed when no bundles to refresh',
        );
    }

    /**
     * @param array $fixtures
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(array $fixtures): QuoteTransfer
    {
        $cartChangeTransfer = new QuoteTransfer();
        $cartChangeTransfer->fromArray($fixtures, true);

        return $cartChangeTransfer;
    }

    /**
     * @return array
     */
    protected function getQuoteTransferFixturesWithBundle(): array
    {
        return [
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => static::EXISTING_BUNDLE_IDENTIFIER,
                    ItemTransfer::QUANTITY => static::ACTUAL_ITEM_QUANTITY,
                ],
                [
                    ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => static::EXISTING_BUNDLE_IDENTIFIER,
                    ItemTransfer::QUANTITY => static::ACTUAL_ITEM_QUANTITY,
                ],
            ],

            QuoteTransfer::BUNDLE_ITEMS => [
                [
                    ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::EXISTING_BUNDLE_IDENTIFIER,
                    ItemTransfer::QUANTITY => static::STALE_BUNDLE_QUANTITY,
                ],
                [
                    ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::NON_EXISTENT_BUNDLE_IDENTIFIER,
                    ItemTransfer::QUANTITY => 1,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getQuoteTransferFixturesWithoutBundle(): array
    {
        return [
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::ID => 1,
                    ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => null,
                    ItemTransfer::QUANTITY => 10,
                ],
                [
                    ItemTransfer::ID => 2,
                    ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => null,
                    ItemTransfer::QUANTITY => 20,
                ],
            ],
            QuoteTransfer::BUNDLE_ITEMS => [],
        ];
    }
}
