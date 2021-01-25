<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Shipment\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class ShipmentStub implements ShipmentStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentMethodsCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer */
        $shipmentMethodsCollectionTransfer = $this->zedStub->call('/shipment/gateway/get-available-methods-by-shipment', $quoteTransfer);

        return $shipmentMethodsCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/shipment/gateway/expand-quote-with-shipment-groups', $quoteTransfer);

        return $quoteTransfer;
    }
}
