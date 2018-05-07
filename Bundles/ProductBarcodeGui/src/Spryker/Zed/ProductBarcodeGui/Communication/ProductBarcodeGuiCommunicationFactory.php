<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductBarcodeGui\Communication\Table\ProductBarcodeTable;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleInterface;
use Spryker\Zed\ProductBarcodeGui\Dependency\Service\ProductBarcodeGuiToBarcodeServiceInterface;
use Spryker\Zed\ProductBarcodeGui\ProductBarcodeGuiDependencyProvider;

class ProductBarcodeGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductBarcodeGui\Communication\Table\ProductBarcodeTable
     */
    public function createProductBarcodeTable(): ProductBarcodeTable
    {
        return new ProductBarcodeTable(
            $this->getBarcodeService(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBarcodeGui\Dependency\Service\ProductBarcodeGuiToBarcodeServiceInterface
     */
    public function getBarcodeService(): ProductBarcodeGuiToBarcodeServiceInterface
    {
        return $this->getProvidedDependency(ProductBarcodeGuiDependencyProvider::SERVICE_BARCODE);
    }

    /**
     * @return \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleInterface
     */
    public function getLocaleFacade(): ProductBarcodeGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ProductBarcodeGuiDependencyProvider::FACADE_LOCALE);
    }
}
