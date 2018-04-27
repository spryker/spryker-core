<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business;

use Spryker\Service\Barcode\BarcodeServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBarcode\Business\ProductBarcodeGenerator\ProductBarcodeGenerator;
use Spryker\Zed\ProductBarcode\Business\ProductBarcodeGenerator\ProductBarcodeGeneratorInterface;
use Spryker\Zed\ProductBarcode\Business\ProductSkuProvider\ProductSkuProvider;
use Spryker\Zed\ProductBarcode\Business\ProductSkuProvider\ProductSkuProviderInterface;
use Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductBridgeInterface;
use Spryker\Zed\ProductBarcode\ProductBarcodeDependencyProvider;

class ProductBarcodeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductBarcode\Business\ProductBarcodeGenerator\ProductBarcodeGeneratorInterface
     */
    public function createProductBarcodeGenerator(): ProductBarcodeGeneratorInterface
    {
        return new ProductBarcodeGenerator(
            $this->getBarcodeService(),
            $this->createProductSkuProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBarcode\Business\ProductSkuProvider\ProductSkuProviderInterface
     */
    public function createProductSkuProvider(): ProductSkuProviderInterface
    {
        return new ProductSkuProvider(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductBridgeInterface
     */
    public function getProductFacade(): ProductBarcodeToProductBridgeInterface
    {
        return $this->getProvidedDependency(ProductBarcodeDependencyProvider::PRODUCT_FACADE);
    }

    /**
     * @return \Spryker\Service\Barcode\BarcodeServiceInterface
     */
    public function getBarcodeService(): BarcodeServiceInterface
    {
        return $this->getProvidedDependency(ProductBarcodeDependencyProvider::BARCODE_SERVICE);
    }
}
