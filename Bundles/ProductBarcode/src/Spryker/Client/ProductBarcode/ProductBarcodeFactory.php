<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBarcode;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductBarcode\Zed\ProductBarcodeStub;

class ProductBarcodeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductBarcode\Zed\ProductBarcodeStub
     */
    public function createProductBarcodeStub()
    {
        return new ProductBarcodeStub($this->getProvidedDependency(ProductBarcodeDependencyProvider::CLIENT_ZED_REQUEST));
    }
}
