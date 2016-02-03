<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOptionDiscountConnector\Business\ProductOptionDiscountConnectorBusinessFactory getFactory()
 */
class ProductOptionDiscountConnectorFacade extends AbstractFacade
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderDiscountAggregator()->aggregate($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createDiscountTotalAmountAggregator()->aggregate($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateProductOptionDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createProductOptionDiscountAggregator()->aggregate($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateProductOptionTaxWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createItemProductOptionsAndDiscountsAggregator()->aggregate($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTaxAmountWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderTaxAmountWithDiscounts()->aggregate($orderTransfer);
    }
}
