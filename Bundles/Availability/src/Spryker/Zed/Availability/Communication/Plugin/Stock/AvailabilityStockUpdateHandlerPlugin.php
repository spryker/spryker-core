<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Plugin\Stock;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StockExtension\Dependency\Plugin\StockUpdateHandlerPluginInterface;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\Availability\Communication\AvailabilityCommunicationFactory getFactory()
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 */
class AvailabilityStockUpdateHandlerPlugin extends AbstractPlugin implements StockUpdateHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Calculates current item availability, taking reserved items into account.
     * - Updates availability for stores where product stock and/or availability are defined.
     * - Stores new availability for concrete product.
     * - Stores sum of all concrete product availability for abstract product.
     * - Touches availability abstract collector if data changed.
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function handle($sku): void
    {
        $this->getFacade()->updateAvailability($sku);
    }
}
