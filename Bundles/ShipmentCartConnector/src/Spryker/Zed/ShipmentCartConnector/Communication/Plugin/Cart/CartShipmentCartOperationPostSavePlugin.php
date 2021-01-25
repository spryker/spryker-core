<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationPostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentCartConnector\Business\ShipmentCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentCartConnector\ShipmentCartConnectorConfig getConfig()
 */
class CartShipmentCartOperationPostSavePlugin extends AbstractPlugin implements CartOperationPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Recalculates shipment expenses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function postSave(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $cartChangeTransfer = (new CartChangeTransfer())->setQuote($quoteTransfer);

        return $this->getFacade()
            ->updateShipmentPrice($cartChangeTransfer)
            ->getQuote();
    }
}
