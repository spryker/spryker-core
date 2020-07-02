<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOffer\Business\Checker\ItemProductOfferChecker;
use Spryker\Zed\ProductOffer\Business\Checker\ItemProductOfferCheckerInterface;
use Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter\InactiveProductOfferItemsFilter;
use Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter\InactiveProductOfferItemsFilterInterface;
use Spryker\Zed\ProductOffer\Business\ProductOffer\ProductOfferWriter;
use Spryker\Zed\ProductOffer\Business\ProductOffer\ProductOfferWriterInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface;
use Spryker\Zed\ProductOffer\ProductOfferDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface getRepository()
 */
class ProductOfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOffer\Business\ProductOffer\ProductOfferWriterInterface
     */
    public function createProductOfferWriter(): ProductOfferWriterInterface
    {
        return new ProductOfferWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter\InactiveProductOfferItemsFilterInterface
     */
    public function createInactiveProductOfferItemsFilter(): InactiveProductOfferItemsFilterInterface
    {
        return new InactiveProductOfferItemsFilter(
            $this->getRepository(),
            $this->getStoreFacade(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Checker\ItemProductOfferCheckerInterface
     */
    public function createItemProductOfferChecker(): ItemProductOfferCheckerInterface
    {
        return new ItemProductOfferChecker($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface
     */
    public function getMessengerFacade(): ProductOfferToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::FACADE_STORE);
    }
}
