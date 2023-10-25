<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business\Reader;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;

interface ShipmentMethodReaderInterface
{
    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getActiveShipmentMethodCollectionTransferForStore(string $storeName): ShipmentMethodCollectionTransfer;

    /**
     * @param list<int> $shipmentMethodIds
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getShipmentMethodCollectionByShipmentMethodIds(array $shipmentMethodIds): ShipmentMethodCollectionTransfer;

    /**
     * @param list<int> $shipmentCarrierIds
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getShipmentMethodCollectionByShipmentCarrierIds(array $shipmentCarrierIds): ShipmentMethodCollectionTransfer;
}
