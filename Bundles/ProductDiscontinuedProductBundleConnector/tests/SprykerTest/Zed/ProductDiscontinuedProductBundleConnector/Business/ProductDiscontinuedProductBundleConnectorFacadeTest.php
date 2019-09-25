<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedProductBundleConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductDiscontinuedProductBundleConnector
 * @group Business
 * @group Facade
 * @group ProductDiscontinuedProductBundleConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductDiscontinuedProductBundleConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductDiscontinuedProductBundleConnector\ProductDiscontinuedProductBundleConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDiscontinueProductBundleRelatedToProductBundled(): void
    {
        // Arrange
        $bundledProduct = $this->tester->haveProduct();
        $bundleProduct = $this->tester->haveProduct();
        (new SpyProductBundle())
            ->setQuantity(1)
            ->setFkProduct($bundleProduct->getIdProductConcrete())
            ->setFkBundledProduct($bundledProduct->getIdProductConcrete())
            ->save();
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($bundledProduct->getIdProductConcrete());

        // Act
        $productDiscontinuedResponseTransfer = $this->tester->getProductDiscontinued()
            ->markProductAsDiscontinued($productDiscontinueRequestTransfer);
        $this->tester->getFacade()->markRelatedBundleAsDiscontinued($productDiscontinuedResponseTransfer->getProductDiscontinued());
        $productDiscontinuedResponseTransfer = $this->tester->getProductDiscontinued()
            ->findProductDiscontinuedByProductId($bundleProduct->getIdProductConcrete());

        // Assert
        $this->assertTrue($productDiscontinuedResponseTransfer->getIsSuccessful());
        $this->assertInstanceOf(ProductDiscontinuedTransfer::class, $productDiscontinuedResponseTransfer->getProductDiscontinued());
    }
}
