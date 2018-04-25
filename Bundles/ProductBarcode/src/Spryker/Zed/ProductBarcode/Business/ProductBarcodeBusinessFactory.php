<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business;

use Spryker\Service\Barcode\BarcodeService;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBarcode\Business\ProductBarcodeGenerator\ProductBarcodeGenerator;
use Spryker\Zed\ProductBarcode\Business\ProductBarcodeGenerator\ProductBarcodeGeneratorInterface;
use Spryker\Zed\ProductBarcode\Business\ProductStockCodeSelector\ProductStockCodeSelector;
use Spryker\Zed\ProductBarcode\Business\ProductStockCodeSelector\ProductStockCodeSelectorInterface;
use Spryker\Zed\ProductBarcode\ProductBarcodeDependencyProvider;

class ProductBarcodeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductBarcode\Business\ProductBarcodeGenerator\ProductBarcodeGeneratorInterface
     */
    public function createProductBarcodeGenerator(): ProductBarcodeGeneratorInterface
    {
        return new ProductBarcodeGenerator($this->getBarcodeService(), $this->createProductStockCodeSelector());
    }

    /**
     * @return \Spryker\Zed\ProductBarcode\Business\ProductStockCodeSelector\ProductStockCodeSelectorInterface
     */
    public function createProductStockCodeSelector(): ProductStockCodeSelectorInterface
    {
        return new ProductStockCodeSelector();
    }

    /**
     * @return \Spryker\Service\Barcode\BarcodeServiceInterface
     */
    protected function getBarcodeService(): BarcodeService
    {
        return $this->getProvidedDependency(ProductBarcodeDependencyProvider::BARCODE_SERVICE);
    }
}
