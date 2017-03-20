<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\SalesAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacade getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 */
class ProductBundlePriceAggregatorPlugin extends AbstractPlugin implements OrderTotalsAggregatePluginInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
         $this->getFacade()->aggregateBundlePrice($orderTransfer);
    }

}
