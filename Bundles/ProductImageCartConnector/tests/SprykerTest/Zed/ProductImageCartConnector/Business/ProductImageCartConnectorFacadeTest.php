<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use SprykerTest\Zed\ProductImageCartConnector\ProductImageCartConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Business
 * @group Facade
 * @group ProductImageCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductImageCartConnectorFacadeTest extends Unit
{
    /**
     * @uses ProductImageCartConnectorConfig::DEFAULT_IMAGE_SET_NAME
     *
     * @type string
     *
     * @var string
     */
    protected const PRODUCT_IMAGE_SET_DEFAULT = 'default';

    /**
     * @var \SprykerTest\Zed\ProductImageCartConnector\ProductImageCartConnectorBusinessTester
     */
    protected ProductImageCartConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandCartChangeItemsExpandsCartItemWithConcreteProductImages(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => static::PRODUCT_IMAGE_SET_DEFAULT,
            ProductImageSetTransfer::LOCALE => null,
        ]);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setProductConcrete($productConcreteTransfer)
                    ->setId($productConcreteTransfer->getIdProductConcrete())
                    ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()),
            );

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->expandCartChangeItems($cartChangeTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $cartChangeTransfer->getItems()->offsetGet(0);

        /** @var \Generated\Shared\Transfer\ProductImageTransfer $imageTransfer */
        $imageTransfer = $itemTransfer->getImages()->offsetGet(0);
        $this->assertInstanceOf(ProductImageTransfer::class, $imageTransfer);
    }

    /**
     * @return void
     */
    public function testExpandCartChangeItemsExpandsCartItemWithAbstractProductImagesIfConcreteProductImagesAreMissing(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
            ProductImageSetTransfer::NAME => static::PRODUCT_IMAGE_SET_DEFAULT,
            ProductImageSetTransfer::LOCALE => null,
        ]);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setProductConcrete($productConcreteTransfer)
                    ->setId($productConcreteTransfer->getIdProductConcrete())
                    ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()),
            );

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->expandCartChangeItems($cartChangeTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $cartChangeTransfer->getItems()->offsetGet(0);

        /** @var \Generated\Shared\Transfer\ProductImageTransfer $imageTransfer */
        $imageTransfer = $itemTransfer->getImages()->offsetGet(0);
        $this->assertInstanceOf(ProductImageTransfer::class, $imageTransfer);
    }
}
