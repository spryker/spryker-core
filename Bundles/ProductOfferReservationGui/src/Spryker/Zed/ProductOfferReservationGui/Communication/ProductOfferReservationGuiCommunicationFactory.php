<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferReservationGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferReservationGui\Dependency\Facade\ProductOfferReservationGuiToOmsProductOfferReservationFacadeInterface;
use Spryker\Zed\ProductOfferReservationGui\ProductOfferReservationGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferReservationGui\ProductOfferReservationGuiConfig getConfig()
 */
class ProductOfferReservationGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferReservationGui\Dependency\Facade\ProductOfferReservationGuiToOmsProductOfferReservationFacadeInterface
     */
    public function getOmsProductOfferReservationFacade(): ProductOfferReservationGuiToOmsProductOfferReservationFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferReservationGuiDependencyProvider::FACADE_OMS_PRODUCT_OFFER_RESERVATION);
    }
}
