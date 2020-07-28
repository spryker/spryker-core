<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductOfferGui\Communication\Reader\PriceProductOfferReader;
use Spryker\Zed\PriceProductOfferGui\Communication\Reader\PriceProductOfferReaderInterface;
use Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceFacadeInterface;
use Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductOfferGui\Dependency\Service\PriceProductOfferGuiToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductOfferGui\PriceProductOfferGuiDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductOfferGui\PriceProductOfferGuiConfig getConfig()
 */
class PriceProductOfferGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PriceProductOfferGui\Communication\Reader\PriceProductOfferReaderInterface
     */
    public function createPriceProductOfferReader(): PriceProductOfferReaderInterface
    {
        return new PriceProductOfferReader(
            $this->getPriceProductFacade(),
            $this->getPriceFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductOfferGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceFacadeInterface
     */
    public function getPriceFacade(): PriceProductOfferGuiToPriceFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferGuiDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferGui\Dependency\Service\PriceProductOfferGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductOfferGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductOfferGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
