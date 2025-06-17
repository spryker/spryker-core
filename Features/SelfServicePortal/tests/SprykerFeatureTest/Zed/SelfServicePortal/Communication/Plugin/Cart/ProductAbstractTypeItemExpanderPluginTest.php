<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\Transfer\ProductAbstractTypeTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Cart\ProductAbstractTypeItemExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group ProductAbstractTypeItemExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractTypeItemExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_TYPE_NAME = 'service';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Cart\ProductAbstractTypeItemExpanderPlugin
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ProductAbstractTypeItemExpanderPlugin();
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
