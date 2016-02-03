<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax\Communication\Plugin\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface;
use Spryker\Zed\Tax\Business\TaxFacade;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacade getFacade()
 */
class ItemTaxAmountAggregatorPlugin extends AbstractPlugin implements OrderTotalsAggregatePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->getFacade()->aggregateOrderItemTaxAmount($orderTransfer);
    }
}
