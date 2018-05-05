<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductBarcodeGui\Communication\Table\ProductBarcodeTable;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleBridgeInterface;
use Spryker\Zed\ProductBarcodeGui\Dependency\Service\ProductBarcodeGuiToBarcodeServiceBridgeInterface;
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
     * @return \Spryker\Zed\ProductBarcodeGui\Dependency\Service\ProductBarcodeGuiToBarcodeServiceBridgeInterface
     */
    public function getBarcodeService(): ProductBarcodeGuiToBarcodeServiceBridgeInterface
    {
        return $this->getProvidedDependency(ProductBarcodeGuiDependencyProvider::BARCODE_SERVICE);
    }

    /**
     * @return \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleBridgeInterface
     */
    public function getLocaleFacade(): ProductBarcodeGuiToLocaleBridgeInterface
    {
        return $this->getProvidedDependency(ProductBarcodeGuiDependencyProvider::LOCALE_FACADE);
    }
}
