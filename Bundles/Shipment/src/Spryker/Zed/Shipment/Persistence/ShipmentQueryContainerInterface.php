<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ShipmentQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function queryCarriers();

    /**
     * @api
     *
     * @deprecated Not used anymore and obsolete.
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function queryActiveCarriers();

    /**
     * @api
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethods();

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface::getActiveShipmentMethods() instead.
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryActiveMethods();

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface::findShipmentMethodById() instead.
     *
     * @param int $idMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethodByIdMethod($idMethod);

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface::findTaxSetByShipmentMethodAndCountryIso2Code() instead.
     *
     * @param int $idShipmentMethod
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryTaxSetByIdShipmentMethodAndCountryIso2Code($idShipmentMethod, $countryIso2Code);

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface::findShipmentTransfersByOrder() instead.
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    public function querySalesShipmentByIdSalesOrder($idSalesOrder);

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $carrierName
     * @param int|null $idCarrier
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function queryUniqueCarrierName($carrierName, $idCarrier = null);

    /**
     * @api
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethodsWithMethodPricesAndCarrier();

    /**
     * @api
     *
     * @param int $idShipmentMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethodWithMethodPricesAndCarrierById($idShipmentMethod);

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface::getActiveShipmentMethods() instead.
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryActiveMethodsWithMethodPricesAndCarrier();

    /**
     * @api
     *
     * @param int $idShipmentMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryActiveMethodsWithMethodPricesAndCarrierById($idShipmentMethod);

    /**
     * @api
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    public function queryMethodPrices();

    /**
     * @api
     *
     * @param int $idShipmentMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    public function queryMethodPricesByIdShipmentMethod($idShipmentMethod);

    /**
     * @api
     *
     * @param int $idShipmentMethod
     * @param int $idStore
     * @param int $idCurrency
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    public function queryMethodPriceByShipmentMethodAndStoreCurrency($idShipmentMethod, $idStore, $idCurrency);

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface::findShipmentMethodByIdWithPricesAndCarrier() instead.
     *
     * @param int $idShipmentMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryActiveShipmentMethodByIdShipmentMethod($idShipmentMethod);
}
