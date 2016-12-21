<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Stock;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacade getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 */
class ProductBundleAvailabilityHandlerPlugin extends AbstractPlugin implements StockUpdateHandlerPluginInterface
{

    /**
     * This plugin handles all necessary events related to stock updates, like Availability.
     *
     * @param string $sku
     *
     * @return void
     */
    public function handle($sku)
    {
        $this->getFacade()
            ->updateBundleAvailability($sku);
    }

}
