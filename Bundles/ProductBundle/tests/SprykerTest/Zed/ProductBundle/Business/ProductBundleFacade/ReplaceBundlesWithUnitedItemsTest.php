<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\ProductBundleFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
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
 * @group ReplaceBundlesWithUnitedItemsTest
 * Add your own group annotations below this line
 */
class ReplaceBundlesWithUnitedItemsTest extends Unit
{
    /**
     * @var int
     */
    protected const BUNDLE_PRODUCT_ID = 1;

    /**
     * @var int
     */
    protected const BUNDLED_PRODUCT_ID = 2;

    /**
     * @var int
     */
    protected const INITIAL_QUANTITY = 5;

    /**
     * @var int
     */
    protected const QUANTITY_TO_REMOVE = 2;

    /**
     * @var int
     */
    protected const BUNDLED_ITEMS_QUANTITY_IN_BUNDLE = 3;

    /**
     * @var string
     */
    protected const BUNDLE_GROUP_KEY = 'bundle-group-key';

    /**
     * @var string
     */
    protected const BUNDLE_IDENTIFIER = 'bundle-identifier';

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBundleReplacedSuccessfullyWhenPresentInCartChangeTransferItems(): void
    {
        // Arrange
        $cartChangeTransfer = $this->createCartChangeTransfer(
            $this->getCartChangeTransferFixturesWithBundle(),
        );

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->replaceBundlesWithUnitedItems($cartChangeTransfer);

        // Assert
        $this->assertTrue(
            $cartChangeTransfer->getItems()->count() === static::BUNDLED_ITEMS_QUANTITY_IN_BUNDLE,
            'Number of elements in CartChangeTransfer.Items should be equal to quantity of bundled products in the bundle',
        );
        foreach ($cartChangeTransfer->getItems() as $cartChangeItemTransfer) {
            $this->assertTrue(
                $cartChangeItemTransfer->getId() === static::BUNDLED_PRODUCT_ID,
                'Bundle item should be replaced with bundled item in CartChangeTransfer.Items',
            );
            $this->assertTrue(
                $cartChangeItemTransfer->getQuantity() === static::QUANTITY_TO_REMOVE,
                'Bundled item quantity in CartChangeTransfer.Items should be equal to the initial quantity to remove',
            );
        }
    }

    /**
     * @return void
     */
    public function testCartChangeTransferNotChangedWhenNoBundlePresent(): void
    {
        // Arrange
        $cartChangeTransfer = $this->createCartChangeTransfer(
            $this->getCartChangeTransferFixturesWithoutBundle(),
        );
        $initialCartChangeTransfer = (new CartChangeTransfer())->fromArray($cartChangeTransfer->toArray());

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->replaceBundlesWithUnitedItems($cartChangeTransfer);

        // Assert
        $this->assertEquals(
            $initialCartChangeTransfer->toArray(),
            $cartChangeTransfer->toArray(),
            'Cart change transfer should not be changed when no bundles to replace',
        );
    }

    /**
     * @param array $fixtures
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(array $fixtures): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->fromArray($fixtures, true);

        return $cartChangeTransfer;
    }

    /**
     * @return array
     */
    protected function getCartChangeTransferFixturesWithBundle(): array
    {
        return [
            CartChangeTransfer::QUOTE => [
                QuoteTransfer::ITEMS => array_fill(0, static::BUNDLED_ITEMS_QUANTITY_IN_BUNDLE, [
                    ItemTransfer::ID => static::BUNDLED_PRODUCT_ID,
                    ItemTransfer::QUANTITY => static::INITIAL_QUANTITY,
                    ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => static::BUNDLE_IDENTIFIER,
                ]),
                QuoteTransfer::BUNDLE_ITEMS => [
                    [
                        ItemTransfer::ID => static::BUNDLE_PRODUCT_ID,
                        ItemTransfer::QUANTITY => static::INITIAL_QUANTITY,
                        ItemTransfer::GROUP_KEY => static::BUNDLE_GROUP_KEY,
                        ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::BUNDLE_IDENTIFIER,
                    ],
                ],
            ],

            CartChangeTransfer::ITEMS => [
                [
                    ItemTransfer::ID => static::BUNDLE_PRODUCT_ID,
                    ItemTransfer::QUANTITY => static::QUANTITY_TO_REMOVE,
                    ItemTransfer::GROUP_KEY => static::BUNDLE_GROUP_KEY,
                    ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::BUNDLE_IDENTIFIER,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getCartChangeTransferFixturesWithoutBundle(): array
    {
        return [
            CartChangeTransfer::QUOTE => [
                QuoteTransfer::ITEMS => [
                    [
                        ItemTransfer::ID => static::BUNDLED_PRODUCT_ID,
                        ItemTransfer::QUANTITY => static::INITIAL_QUANTITY,
                    ],
                ],
                QuoteTransfer::BUNDLE_ITEMS => [],
            ],

            CartChangeTransfer::ITEMS => [
                [
                    ItemTransfer::ID => static::BUNDLED_PRODUCT_ID,
                    ItemTransfer::QUANTITY => static::QUANTITY_TO_REMOVE,
                    ItemTransfer::GROUP_KEY => 'concrete-product-sku',
                    ItemTransfer::BUNDLE_ITEM_IDENTIFIER => null,
                ],
            ],
        ];
    }
}
