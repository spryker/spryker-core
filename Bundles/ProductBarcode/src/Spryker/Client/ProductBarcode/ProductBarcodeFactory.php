<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBarcode;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductBarcode\Dependency\Service\ProductBarcodeToBarcodeServiceInterface;

class ProductBarcodeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductBarcode\Dependency\Service\ProductBarcodeToBarcodeServiceInterface
     */
    public function getBarcodeService(): ProductBarcodeToBarcodeServiceInterface
    {
        return $this->getProvidedDependency(ProductBarcodeDependencyProvider::SERVICE_BARCODE);
    }
}
