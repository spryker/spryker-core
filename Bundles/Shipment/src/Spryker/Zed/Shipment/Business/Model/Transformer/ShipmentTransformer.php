<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentTransformer implements ShipmentTransformerInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     */
    public function __construct(ShipmentQueryContainerInterface $shipmentQueryContainer)
    {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpySalesShipment $shipmentEntity
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function transformEntityToTransfer(SpySalesShipment $shipmentEntity): ShipmentTransfer
    {
        $shipmentTransfer = (new ShipmentTransfer())
            ->fromArray($shipmentEntity->toArray(), true);
           /* ->setCarrier()
            ->setMethod()
            ->setShipmentSelection()
            ->setRequestedDeliveryDate()
            ->setShippingAddress($this->createAddressTransfer($shipmentEntity->getOrder()->get))
            ->setExpense()
            ->setCarrier();*/

        return $shipmentTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createAddressTransfer(SpySalesOrderAddress $salesOrderAddressEntity): AddressTransfer
    {
        $countryEntity = $salesOrderAddressEntity->getCountry();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($salesOrderAddressEntity->toArray(), true);
        $addressTransfer->setIso2Code($countryEntity->getIso2Code());

        $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);
        $addressTransfer->setCountry($countryTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     *
     * @return string|null
     */
    protected function findShipmentCarrierName(SpyShipmentMethod $shipmentMethodEntity)
    {
        /** @var \Orm\Zed\Shipment\Persistence\SpyShipmentCarrier|null $shipmentCarrierEntity */
        $shipmentCarrierEntity = $shipmentMethodEntity->getShipmentCarrier();
        if (!$shipmentCarrierEntity) {
            return null;
        }

        return $shipmentCarrierEntity->getName();
    }
}
