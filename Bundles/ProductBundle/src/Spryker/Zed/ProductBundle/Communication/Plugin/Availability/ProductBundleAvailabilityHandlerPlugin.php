<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Availability;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface;
use Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacade getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 */
class ProductBundleAvailabilityHandlerPlugin extends AbstractPlugin implements ReservationHandlerPluginInterface, StockUpdateHandlerPluginInterface
{

    /**
     *
     * This plugin handles all necessary events related to reservation updates, such as updating Stock, Availability and etc.
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function handle($sku)
    {
        $this->getFacade()->updateBundleAvailability($sku);
    }
}
