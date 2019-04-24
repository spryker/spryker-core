<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

interface ShipmentRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     * @param string $countryIso2Code
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    public function findTaxSetByShipmentMethodAndCountryIso2Code(
        ShipmentMethodTransfer $methodTransfer,
        string $countryIso2Code
    ): ?TaxSetTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer[]
     */
    public function findShipmentTransfersByOrder(OrderTransfer $orderTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer[] $shipmentTransfers
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function findShipmentMethodTransfersByShipment(array $shipmentTransfers): array;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int[][]
     */
    public function getItemIdsGroupedByShipmentIds(OrderTransfer $orderTransfer): array;
}
