<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantity\Communication\Plugin\CartExtension;

use Codeception\Test\Unit;
use Spryker\Zed\ProductQuantity\Communication\Plugin\CartExtension\ProductQuantityRestrictionCartRemovalPreCheckPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductQuantity
 * @group Communication
 * @group Plugin
 * @group CartExtension
 * @group ProductQuantityRestrictionCartRemovalPreCheckPluginTest
 * Add your own group annotations below this line
 */
class ProductQuantityRestrictionCartRemovalPreCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductQuantity\ProductQuantityCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductQuantity\Communication\Plugin\CartExtension\ProductQuantityRestrictionCartRemovalPreCheckPlugin
     */
    protected $productQuantityRestrictionCartRemovalPreCheckPlugin;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productQuantityRestrictionCartRemovalPreCheckPlugin = new ProductQuantityRestrictionCartRemovalPreCheckPlugin();
    }

    /**
     * @return void
     */
    public function testCheckDoesNotThrowExceptionWhenCalledWithEmptyListOfItems()
    {
        // Assign
        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();

        // Act
        $this->productQuantityRestrictionCartRemovalPreCheckPlugin->check($cartChangeTransfer);

        // Assert
        $this->assertTrue(true);
    }
}
