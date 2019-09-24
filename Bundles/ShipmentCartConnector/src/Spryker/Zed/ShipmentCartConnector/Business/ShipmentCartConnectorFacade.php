<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShipmentCartConnector\Business\ShipmentCartConnectorBusinessFactory getFactory()
 */
class ShipmentCartConnectorFacade extends AbstractFacade implements ShipmentCartConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function updateShipmentPrice(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createShipmentCartExpanderStrategyResolver()
            ->resolve($cartChangeTransfer->getQuote()->getItems())
            ->updateShipmentPrice($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateShipment(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createShipmentCartValidatorStrategyResolver()
            ->resolve($cartChangeTransfer->getQuote()->getItems())
            ->validateShipment($cartChangeTransfer);
    }
}
