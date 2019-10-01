<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Shipment;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Shipment\ShipmentFactory getFactory()
 */
class ShipmentClient extends AbstractClient implements ShipmentClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use getAvailableMethodsByShipment() instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->getAvailableMethods($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentMethodsCollectionTransfer
    {
        return $this->getFactory()->createZedStub()->getAvailableMethodsByShipment($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isMultiShipmentSelectionEnabled(): bool
    {
        return $this->getFactory()->getConfig()->isMultiShipmentSelectionEnabled();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createZedStub()->expandQuoteWithShipmentGroups($quoteTransfer);
    }
}
