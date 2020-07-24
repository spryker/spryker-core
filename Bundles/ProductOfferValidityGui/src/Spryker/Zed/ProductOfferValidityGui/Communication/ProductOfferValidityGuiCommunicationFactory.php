<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidityGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferValidityGui\Communication\Reader\ProductOfferValidityGuiReader;
use Spryker\Zed\ProductOfferValidityGui\Communication\Reader\ProductOfferValidityGuiReaderInterface;
use Spryker\Zed\ProductOfferValidityGui\Dependency\Facade\ProductOfferValidityGuiToProductOfferValidityFacadeInterface;
use Spryker\Zed\ProductOfferValidityGui\ProductOfferValidityGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferValidityGui\ProductOfferValidityGuiConfig getConfig()
 */
class ProductOfferValidityGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferValidityGui\Communication\Reader\ProductOfferValidityGuiReaderInterface
     */
    public function createProductOfferValidityGuiReader(): ProductOfferValidityGuiReaderInterface
    {
        return new ProductOfferValidityGuiReader($this->getProductOfferValidityFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferValidityGui\Dependency\Facade\ProductOfferValidityGuiToProductOfferValidityFacadeInterface
     */
    public function getProductOfferValidityFacade(): ProductOfferValidityGuiToProductOfferValidityFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferValidityGuiDependencyProvider::FACADE_PRODUCT_OFFER_VALIDITY);
    }
}
