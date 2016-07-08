<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityBusinessFactory getFactory()
 */
class AvailabilityFacade extends AbstractFacade implements AvailabilityFacadeInterface
{

    /**
     * @api
     *
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->getFactory()->createSellableModel()->isProductSellable($sku, $quantity);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getFactory()->createSellableModel()->calculateStockForProduct($sku);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function checkoutAvailabilityPreCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $this->getFactory()
            ->createProductsAvailablePreCondition()
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku)
    {
        $this->getFactory()->createAvailabilityHandler()->updateAvailability($sku);
    }
}
