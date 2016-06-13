<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOptionDiscountConnector\Business\ProductOptionDiscountConnectorBusinessFactory getFactory()
 */
class ProductOptionDiscountConnectorFacade extends AbstractFacade implements ProductOptionDiscountConnectorFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderDiscountAggregator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createDiscountTotalAmountAggregator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemWithProductOptionsDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createProductOptionDiscountAggregator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemWithProductOptionsAndDiscountsTaxAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createItemProductOptionsAndDiscountsTaxCalculator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalTaxAmountWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderTotalWithDiscountsTaxCalculator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateItemWithProductOptionsAndDiscountsTaxAmount(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createItemProductOptionsAndDiscountsTaxCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateOrderTotalTaxAmountWithDiscounts(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createOrderTotalWithDiscountsTaxCalculator()->recalculate($quoteTransfer);
    }

}
