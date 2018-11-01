<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Offer;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Offer\Dependency\Client\OfferToCustomerClientInterface;
use Spryker\Client\Offer\Model\Hydrator\OfferHydrator;
use Spryker\Client\Offer\Model\Hydrator\OfferHydratorInterface;
use Spryker\Client\Offer\Zed\OfferStub;

class OfferFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Offer\Zed\OfferStubInterface
     */
    public function createZedStub()
    {
        return new OfferStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\Offer\Model\Hydrator\OfferHydratorInterface
     */
    public function createOfferHydrator(): OfferHydratorInterface
    {
        return new OfferHydrator(
            $this->getCustomerClient()
        );
    }

    /**
     * @return \Spryker\Client\Offer\Dependency\Client\OfferToCustomerClientInterface
     */
    public function getCustomerClient(): OfferToCustomerClientInterface
    {
        return $this->getProvidedDependency(OfferDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\Offer\Dependency\Client\OfferToZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(OfferDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
