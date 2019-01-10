<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Shipment\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
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
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodTransfer */
        $shipmentMethodTransfer = $this->zedStub->call('/shipment/gateway/get-available-methods', $quoteTransfer);

        return $shipmentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        $shipmentGroupTransferArray = $this->zedStub->call('/shipment/gateway/get-available-methods-by-shipment', $quoteTransfer);

        return $shipmentGroupTransferArray;
    }
}
