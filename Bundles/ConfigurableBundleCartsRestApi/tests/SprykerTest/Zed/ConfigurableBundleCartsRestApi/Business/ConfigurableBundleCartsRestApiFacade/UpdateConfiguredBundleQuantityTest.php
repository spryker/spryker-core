<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiFacade;

use Codeception\Test\Unit;
use Spryker\Zed\Cart\CartDependencyProvider;
use Spryker\Zed\Cart\Communication\Plugin\SkuGroupKeyPlugin;
use Spryker\Zed\ConfigurableBundleCart\Communication\Plugin\Cart\ConfiguredBundleGroupKeyItemExpanderPlugin;
use Spryker\Zed\ConfigurableBundleCart\Communication\Plugin\Cart\ConfiguredBundleQuantityPerSlotItemExpanderPlugin;
use Spryker\Zed\ConfigurableBundleCart\Communication\Plugin\Cart\ConfiguredBundleQuantityPostSavePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleCartsRestApi
 * @group Business
 * @group ConfigurableBundleCartsRestApiFacade
 * @group UpdateConfiguredBundleQuantityTest
 * Add your own group annotations below this line
 */
class UpdateConfiguredBundleQuantityTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(CartDependencyProvider::CART_EXPANDER_PLUGINS, [
            new SkuGroupKeyPlugin(),
            new ConfiguredBundleQuantityPerSlotItemExpanderPlugin(),
            new ConfiguredBundleGroupKeyItemExpanderPlugin(),
        ]);

        $this->tester->setDependency(CartDependencyProvider::CART_POST_SAVE_PLUGINS, [
            new ConfiguredBundleQuantityPostSavePlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityUpdatesConfiguredBundleQuantity(): void
    {
        // Arrange
        $bundleQuantity = 2;
        $createConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest($bundleQuantity);

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateConfiguredBundleQuantity($createConfiguredBundleRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());

        /** @var \Generated\Shared\Transfer\ItemTransfer $firstItemTransfer */
        $firstItemTransfer = $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0);

        /** @var \Generated\Shared\Transfer\ItemTransfer $secondItemTransfer */
        $secondItemTransfer = $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1);

        $this->assertSame($bundleQuantity, $firstItemTransfer->getConfiguredBundle()->getQuantity());
        $this->assertSame($bundleQuantity, $secondItemTransfer->getConfiguredBundle()->getQuantity());

        $this->assertSame(
            $bundleQuantity * $firstItemTransfer->getConfiguredBundleItem()->getQuantityPerSlot(),
            $firstItemTransfer->getQuantity()
        );
        $this->assertSame(
            $bundleQuantity * $secondItemTransfer->getConfiguredBundleItem()->getQuantityPerSlot(),
            $secondItemTransfer->getQuantity()
        );
    }
}
