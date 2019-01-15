<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Transfer;

use Spryker\Zed\Shipment\Business\Model\MethodReaderInterface;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class TransferBuilder
{
    /**
     * @var \Spryker\Zed\Shipment\Business\Model\MethodReaderInterface
     */
    protected $methodReader;

    /**
     * @param \Spryker\Zed\Shipment\Business\Model\MethodReaderInterface $methodReader
     */
    public function __construct(MethodReaderInterface $methodReader)
    {
        $this->methodReader = $methodReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $qoute
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getAvailableMethodsByShipmentGroups(QuoteTransfer $qoute): ShipmentGroupCollectionTransfer
    {
        return (new ShipmentGroupCollectionTransfer())->setGroups(
            $this->methodReader->getAvailableMethodsByShipment($qoute)
        );
    }
}