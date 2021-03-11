<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductOfferGui\Communication\Expander\MerchantListDataExpander;
use Spryker\Zed\MerchantProductOfferGui\Communication\Expander\MerchantListDataExpanderInterface;
use Spryker\Zed\MerchantProductOfferGui\Communication\Expander\MerchantProductOfferTableExpander;
use Spryker\Zed\MerchantProductOfferGui\Communication\Expander\MerchantProductOfferTableExpanderInterface;
use Spryker\Zed\MerchantProductOfferGui\Communication\Reader\MerchantProductOfferReader;
use Spryker\Zed\MerchantProductOfferGui\Communication\Reader\MerchantProductOfferReaderInterface;
use Spryker\Zed\MerchantProductOfferGui\Dependency\Facade\MerchantProductOfferGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductOfferGui\MerchantProductOfferGuiDependencyProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Zed\MerchantProductOfferGui\MerchantProductOfferGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferGui\Persistence\MerchantProductOfferGuiRepositoryInterface getRepository()
 */
class MerchantProductOfferGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferGui\Communication\Expander\MerchantProductOfferTableExpanderInterface
     */
    public function createMerchantProductOfferTableExpander(): MerchantProductOfferTableExpanderInterface
    {
        return new MerchantProductOfferTableExpander(
            $this->getRepository(),
            $this->getRequest()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return $this->getRequestStack()->getCurrentRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(MerchantProductOfferGuiDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferGui\Communication\Reader\MerchantProductOfferReaderInterface
     */
    public function createMerchantProductOfferReader(): MerchantProductOfferReaderInterface
    {
        return new MerchantProductOfferReader($this->getMerchantFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferGui\Dependency\Facade\MerchantProductOfferGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProductOfferGuiToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferGuiDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferGui\Communication\Expander\MerchantListDataExpanderInterface
     */
    public function createMerchantListDataExpander(): MerchantListDataExpanderInterface
    {
        return new MerchantListDataExpander($this->getMerchantFacade());
    }
}
