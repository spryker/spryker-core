<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductOfferGui\Communication\Reader\MerchantProductOfferGuiReader;
use Spryker\Zed\MerchantProductOfferGui\Communication\Reader\MerchantProductOfferGuiReaderInterface;
use Spryker\Zed\MerchantProductOfferGui\Dependency\Facade\MerchantProductOfferGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductOfferGui\MerchantProductOfferGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOfferGui\MerchantProductOfferGuiConfig getConfig()
 */
class MerchantProductOfferGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferGui\Communication\Reader\MerchantProductOfferGuiReaderInterface
     */
    public function createMerchantProductOfferGuiReader(): MerchantProductOfferGuiReaderInterface
    {
        return new MerchantProductOfferGuiReader($this->getMerchantFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferGui\Dependency\Facade\MerchantProductOfferGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProductOfferGuiToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferGuiDependencyProvider::FACADE_MERCHANT);
    }
}
