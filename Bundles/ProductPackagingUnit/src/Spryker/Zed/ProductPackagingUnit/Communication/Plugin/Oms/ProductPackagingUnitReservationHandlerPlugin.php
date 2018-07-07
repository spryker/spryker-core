<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Oms;

use Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnit\Communication\ProductPackagingUnitCommunicationFactory getFactory()
 */
class ProductPackagingUnitReservationHandlerPlugin implements ReservationHandlerPluginInterface
{
    /**
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function handle($sku)
    {
        $this->getFacade()->updateProductPackagingUnitLeadProductAvailability($sku);
    }
}
