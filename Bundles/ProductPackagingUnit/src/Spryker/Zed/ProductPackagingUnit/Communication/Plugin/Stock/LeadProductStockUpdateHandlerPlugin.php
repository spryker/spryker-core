<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Stock;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnit\Communication\ProductPackagingUnitCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class LeadProductStockUpdateHandlerPlugin extends AbstractPlugin implements StockUpdateHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates the lead product's availability for the provided product packaging unit SKU.
     * - Skips updating if the product packaging unit has self as lead product.
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function handle($sku)
    {
        $this->getFacade()->updateLeadProductAvailability($sku);
    }
}
