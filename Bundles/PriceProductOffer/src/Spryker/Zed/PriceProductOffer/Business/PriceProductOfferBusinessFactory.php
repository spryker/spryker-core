<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductOffer\Business\Expander\ProductOfferExpander;
use Spryker\Zed\PriceProductOffer\Business\Expander\ProductOfferExpanderInterface;
use Spryker\Zed\PriceProductOffer\Business\Writer\PriceProductOfferWriter;
use Spryker\Zed\PriceProductOffer\Business\Writer\PriceProductOfferWriterInterface;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductOffer\PriceProductOfferDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductOffer\PriceProductOfferConfig getConfig()
 */
class PriceProductOfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductOffer\Business\Writer\PriceProductOfferWriterInterface
     */
    public function createPriceProductOfferWriter(): PriceProductOfferWriterInterface
    {
        return new PriceProductOfferWriter(
            $this->getPriceProductFacade(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Business\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductOfferToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
