<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityOfferConnector\Business;

use Spryker\Zed\AvailabilityOfferConnector\AvailabilityOfferConnectorDependencyProvider;
use Spryker\Zed\AvailabilityOfferConnector\Business\Model\OfferQuoteItemStockHydrator;
use Spryker\Zed\AvailabilityOfferConnector\Business\Model\OfferQuoteItemStockHydratorInterface;
use Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToAvailabilityFacadeInterface;
use Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToStoreFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class AvailabilityOfferConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityOfferConnector\Business\Model\OfferQuoteItemStockHydratorInterface
     */
    public function createOfferQuoteItemStockHydrator(): OfferQuoteItemStockHydratorInterface
    {
        return new OfferQuoteItemStockHydrator(
            $this->getStoreFacade(),
            $this->getAvailabilityFacade()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToStoreFacadeInterface
     */
    public function getStoreFacade(): AvailabilityOfferConnectorToStoreFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityOfferConnectorDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToAvailabilityFacadeInterface
     */
    public function getAvailabilityFacade(): AvailabilityOfferConnectorToAvailabilityFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityOfferConnectorDependencyProvider::FACADE_AVAILABILITY);
    }
}
