<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Product
 * @group ActivationTest
 */
class ActivationTest extends FacadeTestAbstract
{

    /**
     * @return void
     */
    public function testProductActivationShouldGenerateUrlAndTouch()
    {
        $idProductAbstract = $this->createNewProduct();
        $productConcreteCollection = $this->productConcreteManager->getConcreteProductsByAbstractProductId($idProductAbstract);
        $this->assertNotEmpty($productConcreteCollection);

        foreach ($productConcreteCollection as $productConcreteTransfer) {
            $this->assertNotTrue($productConcreteTransfer->getIsActive());

            $this->productFacade->activateProductConcrete($productConcreteTransfer->getIdProductConcrete());

            $this->assertProductWasActivated($productConcreteTransfer);
        }
    }

    /**
     * @return void
     */
    public function testProductDeactivationShouldGenerateUrlAndTouch()
    {
        $idProductAbstract = $this->createNewActiveProduct();
        $productConcreteCollection = $this->productConcreteManager->getConcreteProductsByAbstractProductId($idProductAbstract);
        $this->assertNotEmpty($productConcreteCollection);

        foreach ($productConcreteCollection as $productConcreteTransfer) {
            $this->assertTrue($productConcreteTransfer->getIsActive());

            $this->productFacade->deactivateProductConcrete($productConcreteTransfer->getIdProductConcrete());

            $this->assertProductWasDeactivated($productConcreteTransfer);
        }
    }

    /**
     * @return void
     */
    public function testProductActivationShouldThrowException()
    {
        $this->expectException(ProductConcreteNotFoundException::class);
        $this->expectExceptionMessage('Could not activate product concrete [12324]');

        $this->productFacade->activateProductConcrete(12324);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assertProductWasActivated(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcrete = $this->productConcreteManager->getProductConcreteById(
            $productConcreteTransfer->getIdProductConcrete()
        );

        $this->assertTrue($productConcrete->getIsActive());

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $productConcrete->getFkProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            $this->assertNotNull($urlTransfer->getIdUrl());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assertProductWasDeactivated(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcrete = $this->productConcreteManager->getProductConcreteById(
            $productConcreteTransfer->getIdProductConcrete()
        );

        $this->assertFalse($productConcrete->getIsActive());

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $productConcrete->getFkProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            $this->assertNull($urlTransfer->getIdUrl());
        }
    }

    /**
     * @return int
     */
    protected function createNewProduct()
    {
        return $this->productManager->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);
    }

    /**
     * @return int
     */
    protected function createNewActiveProduct()
    {
        $this->productAbstractTransfer->setIsActive(true);
        $this->productConcreteTransfer->setIsActive(true);

        return $this->productManager->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);
    }

}
