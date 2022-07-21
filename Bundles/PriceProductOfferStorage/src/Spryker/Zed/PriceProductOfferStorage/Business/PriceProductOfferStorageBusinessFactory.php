<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorage\PriceProductOfferStorageWriter;
use Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorage\PriceProductOfferStorageWriterInterface;
use Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventFacadeInterface;
use Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToPriceProductOfferFacadeInterface;
use Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageConfig getConfig()
 */
class PriceProductOfferStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorage\PriceProductOfferStorageWriterInterface
     */
    public function createPriceProductOfferStorageWriter(): PriceProductOfferStorageWriterInterface
    {
        return new PriceProductOfferStorageWriter(
            $this->getEventFacade(),
            $this->getPriceProductOfferFacade(),
            $this->getEventBehaviorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventFacadeInterface
     */
    public function getEventFacade(): PriceProductOfferStorageToEventFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferStorageDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToPriceProductOfferFacadeInterface
     */
    public function getPriceProductOfferFacade(): PriceProductOfferStorageToPriceProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferStorageDependencyProvider::FACADE_PRICE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): PriceProductOfferStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
