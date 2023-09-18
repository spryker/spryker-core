<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferStock\Business\Expander\ProductOfferExpander;
use Spryker\Zed\ProductOfferStock\Business\Expander\ProductOfferExpanderInterface;
use Spryker\Zed\ProductOfferStock\Business\Mapper\ProductOfferStockResultMapper;
use Spryker\Zed\ProductOfferStock\Business\Mapper\ProductOfferStockResultMapperInterface;
use Spryker\Zed\ProductOfferStock\Business\Reader\ProductOfferStockReader;
use Spryker\Zed\ProductOfferStock\Business\Reader\ProductOfferStockReaderInterface;
use Spryker\Zed\ProductOfferStock\ProductOfferStockDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferStock\ProductOfferStockConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface getRepository()
 */
class ProductOfferStockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferStock\Business\Reader\ProductOfferStockReaderInterface
     */
    public function createProductOfferStockReader(): ProductOfferStockReaderInterface
    {
        return new ProductOfferStockReader(
            $this->getRepository(),
            $this->createProductOfferStockResultMapper(),
            $this->getStockTransferProductOfferStockExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferStock\Business\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOfferStock\Business\Mapper\ProductOfferStockResultMapperInterface
     */
    public function createProductOfferStockResultMapper(): ProductOfferStockResultMapperInterface
    {
        return new ProductOfferStockResultMapper();
    }

    /**
     * @return array<int, \Spryker\Zed\ProductOfferStockExtension\Dependency\Plugin\StockTransferProductOfferStockExpanderPluginInterface>
     */
    public function getStockTransferProductOfferStockExpanderPlugins(): array
    {
        return $this->getProvidedDependency(
            ProductOfferStockDependencyProvider::PLUGINS_STOCK_TRANSFER_PRODUCT_OFFER_STOCK_EXPANDER,
        );
    }
}
