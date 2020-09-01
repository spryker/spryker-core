<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferStockGui\Communication\Reader\ProductOfferStock\ProductOfferStockReader;
use Spryker\Zed\ProductOfferStockGui\Communication\Reader\ProductOfferStock\ProductOfferStockReaderInterface;
use Spryker\Zed\ProductOfferStockGui\Dependency\Facade\ProductOfferStockGuiToProductOfferStockFacadeInterface;
use Spryker\Zed\ProductOfferStockGui\ProductOfferStockGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferStockGui\ProductOfferStockGuiConfig getConfig()
 */
class ProductOfferStockGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferStockGui\Communication\Reader\ProductOfferStock\ProductOfferStockReaderInterface
     */
    public function createProductOfferStockReader(): ProductOfferStockReaderInterface
    {
        return new ProductOfferStockReader(
            $this->getProductOfferStockFacade(),
            $this->getProductOfferStockTableExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferStockGui\Dependency\Facade\ProductOfferStockGuiToProductOfferStockFacadeInterface
     */
    public function getProductOfferStockFacade(): ProductOfferStockGuiToProductOfferStockFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferStockGuiDependencyProvider::FACADE_PRODUCT_OFFER_STOCK);
    }

    /**
     * @return \Spryker\Zed\ProductOfferStockGuiExtension\Dependeency\Plugin\ProductOfferStockTableExpanderPluginInterface[]
     */
    public function getProductOfferStockTableExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferStockGuiDependencyProvider::PLUGINS_PRODUCT_OFFER_STOCK_TABLE_EXPANDER);
    }
}
