<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBarcode\Business\ProductBarcodeNumberResolver\ProductBarcodeNumberResolver;
use Spryker\Zed\ProductBarcode\ProductBarcodeDependencyProvider;

class ProductBarcodeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductBarcode\Business\ProductBarcodeNumberResolver\ProductBarcodeNumberResolverInterface
     */
    public function createProductBarcodeNumberResolver(): ProductBarcodeNumberResolver
    {
        return new ProductBarcodeNumberResolver();
    }

    /**
     * @return \Spryker\Service\Barcode\BarcodeServiceInterface
     */
    public function getBarcodeGeneratorService()
    {
        return $this->getProvidedDependency(ProductBarcodeDependencyProvider::BARCODE_GENERATOR_SERVICE);
    }
}
