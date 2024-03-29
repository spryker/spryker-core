<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductBarcodeGui\Communication\Table\ProductBarcodeTable;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToProductBarcodeFacadeInterface;
use Spryker\Zed\ProductBarcodeGui\ProductBarcodeGuiDependencyProvider;

class ProductBarcodeGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductBarcodeGui\Communication\Table\ProductBarcodeTable
     */
    public function createProductBarcodeTable(): ProductBarcodeTable
    {
        return new ProductBarcodeTable(
            $this->getProductBarcodeFacade(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToProductBarcodeFacadeInterface
     */
    public function getProductBarcodeFacade(): ProductBarcodeGuiToProductBarcodeFacadeInterface
    {
        return $this->getProvidedDependency(ProductBarcodeGuiDependencyProvider::FACADE_PRODUCT_BARCODE);
    }

    /**
     * @return \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductBarcodeGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductBarcodeGuiDependencyProvider::FACADE_LOCALE);
    }
}
