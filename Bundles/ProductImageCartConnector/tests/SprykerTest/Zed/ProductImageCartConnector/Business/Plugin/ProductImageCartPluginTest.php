<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImageCartConnector\Business\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\ProductImageCartConnector\Business\ProductImageCartConnectorFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImageCartConnector
 * @group Business
 * @group Plugin
 * @group ProductImageCartPluginTest
 * Add your own group annotations below this line
 */
class ProductImageCartPluginTest extends Unit
{
    /**
     * @var \Spryker\Zed\ProductImageCartConnector\Business\ProductImageCartConnectorFacadeInterface
     */
    protected $productImageCartConnectorFacade;

    /**
     * @var \SprykerTest\Zed\ProductImageCartConnector\ProductImageCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productImageCartConnectorFacade = new ProductImageCartConnectorFacade();
    }

    /**
     * @return void
     */
    public function testPluginExpandsCartItemWithImages(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'default',
        ]);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setProductConcrete($productConcreteTransfer)
                    ->setId($productConcreteTransfer->getIdProductConcrete()),
            );

        // Act
        $cartChangeTransfer = $this->productImageCartConnectorFacade->expandItems($cartChangeTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $cartChangeTransfer->getItems()->offsetGet(0);

        /** @var \Generated\Shared\Transfer\ProductImageTransfer $imageTransfer */
        $imageTransfer = $itemTransfer->getImages()->offsetGet(0);
        $this->assertInstanceOf(ProductImageTransfer::class, $imageTransfer);
    }
}
