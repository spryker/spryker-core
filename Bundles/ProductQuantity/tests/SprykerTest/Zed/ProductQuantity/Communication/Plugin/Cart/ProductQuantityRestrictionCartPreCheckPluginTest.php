<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantity\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Spryker\Zed\ProductQuantity\Communication\Plugin\Cart\ProductQuantityRestrictionCartPreCheckPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductQuantity
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group ProductQuantityRestrictionCartPreCheckPluginTest
 * Add your own group annotations below this line
 */
class ProductQuantityRestrictionCartPreCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductQuantity\ProductQuantityCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductQuantity\Communication\Plugin\Cart\ProductQuantityRestrictionCartPreCheckPlugin
     */
    protected $productQuantityRestrictionCartPreCheckPlugin;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productQuantityRestrictionCartPreCheckPlugin = new ProductQuantityRestrictionCartPreCheckPlugin();
    }

    /**
     * @return void
     */
    public function testCheckDoesNotThrowExceptionWhenCalledWithEmptyListOfItems()
    {
        // Assign
        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();

        // Act
        $this->productQuantityRestrictionCartPreCheckPlugin->check($cartChangeTransfer);

        // Assert
        $this->assertTrue(true);
    }
}
