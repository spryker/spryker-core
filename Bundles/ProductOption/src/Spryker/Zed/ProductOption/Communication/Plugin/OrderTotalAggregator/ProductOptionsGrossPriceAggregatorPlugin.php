<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Communication\Plugin\OrderTotalAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOption\Business\ProductOptionFacade;
use Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface;

/**
 * @method ProductOptionFacade getFacade()
 */
class ProductOptionsGrossPriceAggregatorPlugin extends AbstractPlugin implements OrderTotalsAggregatePluginInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->getFacade()->aggregateOrderItemProductOptionGrossPrice($orderTransfer);
    }
}
