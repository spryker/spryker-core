<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferValidity\Business\ProductOffer\ProductOfferSwitcher;
use Spryker\Zed\ProductOfferValidity\Business\ProductOffer\ProductOfferSwitcherInterface;
use Spryker\Zed\ProductOfferValidity\Dependency\Facade\ProductOfferValidityToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferValidity\ProductOfferValidityDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferValidity\ProductOfferValidityConfig getConfig()
 * @method \Spryker\Zed\ProductOfferValidity\Persistence\ProductOfferValidityRepositoryInterface getRepository()
 */
class ProductOfferValidityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferValidity\Business\ProductOffer\ProductOfferSwitcherInterface
     */
    public function createProductOfferSwitcher(): ProductOfferSwitcherInterface
    {
        return new ProductOfferSwitcher(
            $this->getRepository(),
            $this->getProductOfferFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferValidity\Dependency\Facade\ProductOfferValidityToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferValidityToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferValidityDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
