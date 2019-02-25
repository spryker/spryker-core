<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShipmentCheckoutConnector\Business\ShipmentCheckoutConnectorBusinessFactory getFactory()
 */
class ShipmentCheckoutConnectorFacade extends AbstractFacade implements ShipmentCheckoutConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkShipment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        return $this->getFactory()
            ->createShipmentCheckoutPreCheckStrategyResolver()
            ->resolve($quoteTransfer->getItems())
            ->checkShipment($quoteTransfer, $checkoutResponseTransfer);
    }
}
