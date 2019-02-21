<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Transfer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface;

class TransferBuilder
{
    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface
     */
    protected $methodReader;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface $methodReader
     */
    public function __construct(MethodReaderInterface $methodReader)
    {
        $this->methodReader = $methodReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getAvailableMethodsByShipmentGroups(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        return $this->methodReader->getAvailableMethodsByShipment($quoteTransfer);
    }
}
