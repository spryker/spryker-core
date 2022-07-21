<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorageFacadeInterface getFacade()
 */
class PriceProductOfferStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): PriceProductOfferStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
