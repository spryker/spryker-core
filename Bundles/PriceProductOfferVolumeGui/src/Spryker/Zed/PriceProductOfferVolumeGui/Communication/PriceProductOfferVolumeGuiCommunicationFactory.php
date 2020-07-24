<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolumeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Client\PriceProductOfferVolumeGuiToPriceProductOfferVolumeClientInterface;
use Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Facade\PriceProductOfferVolumeGuiToProductOfferFacadeInterface;
use Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Service\PriceProductOfferVolumeGuiToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductOfferVolumeGui\PriceProductOfferVolumeGuiDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductOfferVolumeGui\PriceProductOfferVolumeGuiConfig getConfig()
 */
class PriceProductOfferVolumeGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Facade\PriceProductOfferVolumeGuiToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): PriceProductOfferVolumeGuiToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferVolumeGuiDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Client\PriceProductOfferVolumeGuiToPriceProductOfferVolumeClientInterface
     */
    public function getPriceProductOfferVolumeClient(): PriceProductOfferVolumeGuiToPriceProductOfferVolumeClientInterface
    {
        return $this->getProvidedDependency(PriceProductOfferVolumeGuiDependencyProvider::CLIENT_PRICE_PRODUCT_OFFER_VOLUME);
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Service\PriceProductOfferVolumeGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductOfferVolumeGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductOfferVolumeGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
