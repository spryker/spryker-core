<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferStock\Business\Expander\ProductOfferExpander;
use Spryker\Zed\ProductOfferStock\Business\Expander\ProductOfferExpanderInterface;
use Spryker\Zed\ProductOfferStock\Business\Mapper\ProductOfferStockResultMapper;
use Spryker\Zed\ProductOfferStock\Business\Reader\ProductOfferStockReader;
use Spryker\Zed\ProductOfferStock\Business\Reader\ProductOfferStockReaderInterface;

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
        return new ProductOfferStockReader($this->getRepository(), $this->createProductOfferStockResultMapper());
    }

    /**
     * @return \Spryker\Zed\ProductOfferStock\Business\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOfferStock\Business\Mapper\ProductOfferStockResultMapper
     */
    public function createProductOfferStockResultMapper(): ProductOfferStockResultMapper
    {
        return new ProductOfferStockResultMapper();
    }
}
