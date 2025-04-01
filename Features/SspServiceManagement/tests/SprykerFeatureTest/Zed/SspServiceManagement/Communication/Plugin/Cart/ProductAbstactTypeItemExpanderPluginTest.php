<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractTypeTransfer;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Cart\ProductAbstactTypeItemExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group SspProductAbstactTypeItemExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstactTypeItemExpanderPluginTest extends Unit
{
 /**
  * @var string
  */
    protected const PRODUCT_TYPE_NAME = 'service';

    /**
     * @var \SprykerFeatureTest\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationTester
     */
    protected $tester;

    /**
     * @var \SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Cart\ProductAbstactTypeItemExpanderPlugin
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ProductAbstactTypeItemExpanderPlugin();
    }

    /**
     * @return void
     */
    public function testExpandItemsWillExpandItemsWithProductTypes(): void
    {
        // Arrange

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTypeTransfer = $this->tester->haveProductAbstractType([
            ProductAbstractTypeTransfer::NAME => static::PRODUCT_TYPE_NAME,
        ]);
        $productAbstractTransfer = $this->tester->addProductAbstractTypesToProductAbstract(
            $productAbstractTransfer,
            [$productAbstractTypeTransfer],
        );

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
        ]))->build();

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withItem([])
            ->build();

        $cartChangeTransfer->getItems()[0]->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());

        // Act
        $resultCartChangeTransfer = $this->plugin->expandItems($cartChangeTransfer);

        // Assert
        $this->assertCount(1, $resultCartChangeTransfer->getItems());
        $this->assertCount(1, $resultCartChangeTransfer->getItems()[0]->getProductTypes());
        $this->assertSame(
            static::PRODUCT_TYPE_NAME,
            $resultCartChangeTransfer->getItems()[0]->getProductTypes()[0],
        );
    }

    /**
     * @return void
     */
    public function testExpandItemsWillNotExpandItemsWithoutProductAbstractId(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeBuilder())
            ->withItem([])
            ->build();

        // Act
        $resultCartChangeTransfer = $this->plugin->expandItems($cartChangeTransfer);

        // Assert
        $this->assertCount(1, $resultCartChangeTransfer->getItems());
        $this->assertCount(0, $resultCartChangeTransfer->getItems()[0]->getProductTypes());
    }
}
