<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImageCartConnector\Business\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productImageCartConnectorFacade = new ProductImageCartConnectorFacade();
    }

    /**
     * @return void
     */
    public function testPluginExpandsCartItemWithImages()
    {
        $productTransfer = new ProductConcreteTransfer();
        $productTransfer->setIdProductConcrete(66);

        $changeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setProductConcrete($productTransfer);
        $itemTransfer->setId($productTransfer->getIdProductConcrete());
        $changeTransfer->addItem($itemTransfer);

        $this->productImageCartConnectorFacade->expandItems($changeTransfer);

        $itemTransfer = $changeTransfer->getItems()[0];

        /** @var \Generated\Shared\Transfer\ProductImageTransfer $imageTransfer */
        $imageTransfer = $itemTransfer->getImages()->offsetGet(0);
        $this->assertInstanceOf(ProductImageTransfer::class, $imageTransfer);
    }
}
