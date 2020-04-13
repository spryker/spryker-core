<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation\OmsProductOfferReservationReader;
use Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation\OmsProductOfferReservationReaderInterface;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\OmsProductOfferReservationConfig getConfig()
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface getRepository()
 */
class OmsProductOfferReservationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation\OmsProductOfferReservationReaderInterface
     */
    public function createOmsProductOfferReservationReader(): OmsProductOfferReservationReaderInterface
    {
        return new OmsProductOfferReservationReader($this->getRepository());
    }
}
