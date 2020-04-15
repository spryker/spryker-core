<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OmsProductOfferReservation\Business\Mapper\OmsProductOfferReservationBusinessMapper;
use Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation\OmsProductOfferReservationReader;
use Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation\OmsProductOfferReservationReaderInterface;
use Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation\OmsProductOfferReservationWriter;
use Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation\OmsProductOfferReservationWriterInterface;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\OmsProductOfferReservationConfig getConfig()
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface getRepository()
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationEntityManagerInterface getEntityManager()
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

    /**
     * @return \Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation\OmsProductOfferReservationWriterInterface
     */
    public function createOmsProductOfferReservationWriter(): OmsProductOfferReservationWriterInterface
    {
        return new OmsProductOfferReservationWriter(
            $this->getEntityManager(),
            $this->createOmsProductOfferReservationBusinessMapper()
        );
    }

    /**
     * @return \Spryker\Zed\OmsProductOfferReservation\Business\Mapper\OmsProductOfferReservationBusinessMapper
     */
    public function createOmsProductOfferReservationBusinessMapper(): OmsProductOfferReservationBusinessMapper
    {
        return new OmsProductOfferReservationBusinessMapper();
    }
}
