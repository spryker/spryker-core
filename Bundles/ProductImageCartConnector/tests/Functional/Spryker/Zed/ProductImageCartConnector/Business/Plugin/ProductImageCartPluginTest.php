<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductImageCartConnector\Business\Plugin;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\ProductImageCartConnector\Business\ProductImageCartConnectorFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductImageCartConnector
 * @group Business
 * @group Plugin
 * @group ProductImageCartPluginTest
 */
class ProductImageCartPluginTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacade
     */
    private $productImageCartConnectorFacade;

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
        $changeTransfer->addItem($itemTransfer);

        $this->productImageCartConnectorFacade->expandItems($changeTransfer);

        $itemTransfer = $changeTransfer->getItems()[0];

        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $imageSetTransfer */
        $imageSetTransfer = $itemTransfer->getImageSets()->offsetGet(0);
        $this->assertInstanceOf(ProductImageSetTransfer::class, $imageSetTransfer);


        /** @var \Generated\Shared\Transfer\ProductImageTransfer $imageTransfer */
        $imageTransfer = $imageSetTransfer->getProductImages()->offsetGet(0);
        $this->assertInstanceOf(ProductImageTransfer::class, $imageTransfer);

    }

}
