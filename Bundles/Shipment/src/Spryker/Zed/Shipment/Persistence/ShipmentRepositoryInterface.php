<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;

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

    /**
     * @param int $idSalesShipment
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    public function querySalesShipmentById(int $idSalesShipment): SpySalesShipmentQuery;

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethodsWithMethodPricesAndCarrier(): SpyShipmentMethodQuery;

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryActiveMethodsWithMethodPricesAndCarrier(): SpyShipmentMethodQuery;

    /**
     * @param int $idShipmentMethod
     * @param int $idStore
     * @param int $idCurrency
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    public function queryMethodPriceByShipmentMethodAndStoreCurrency(
        int $idShipmentMethod,
        int $idStore,
        int $idCurrency
    ): SpyShipmentMethodPriceQuery;

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    public function queryMethodPrices(): SpyShipmentMethodPriceQuery;
}
