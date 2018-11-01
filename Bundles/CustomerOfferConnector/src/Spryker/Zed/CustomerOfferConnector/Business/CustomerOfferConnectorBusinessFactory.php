<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerOfferConnector\Business;

use Spryker\Zed\CustomerOfferConnector\Business\Model\OfferCustomerHydrator;
use Spryker\Zed\CustomerOfferConnector\Business\Model\OfferCustomerHydratorInterface;
use Spryker\Zed\CustomerOfferConnector\CustomerOfferConnectorDependencyProvider;
use Spryker\Zed\CustomerOfferConnector\Dependency\Facade\CustomerOfferConnectorToCustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class CustomerOfferConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerOfferConnector\Business\Model\OfferCustomerHydratorInterface
     */
    public function createOfferCustomerHydrator(): OfferCustomerHydratorInterface
    {
        return new OfferCustomerHydrator(
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CustomerOfferConnector\Dependency\Facade\CustomerOfferConnectorToCustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerOfferConnectorToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(CustomerOfferConnectorDependencyProvider::FACADE_CUSTOMER);
    }
}
