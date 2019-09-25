<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Reservation;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnit\Communication\ProductPackagingUnitCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class LeadProductReservationHandlerPlugin extends AbstractPlugin implements ReservationHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates the lead product's reservation for the provided product packaging unit SKU.
     * - Updates the lead product's availability for the provided product packaging unit SKU.
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function handle($sku): void
    {
        $this->getFacade()
            ->updateLeadProductReservation($sku);

        $this->getFacade()
            ->updateLeadProductAvailability($sku);
    }
}
