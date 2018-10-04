<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface;
use Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\Availability\Communication\AvailabilityCommunicationFactory getFactory()
 */
class AvailabilityHandlerPlugin extends AbstractPlugin implements ReservationHandlerPluginInterface, StockUpdateHandlerPluginInterface
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
        $this->getFacade()->updateAvailability($sku);
    }
}
