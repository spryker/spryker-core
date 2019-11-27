<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;

class ShipmentMapper
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapShipmentTransferToShipmentEntity(
        ShipmentTransfer $shipmentTransfer,
        SpySalesShipment $salesShipmentEntity
    ): SpySalesShipment {
        $salesShipmentEntity->fromArray($shipmentTransfer->modifiedToArray());

        $shipmentMethodTransfer = $shipmentTransfer->getMethod();
        if ($shipmentMethodTransfer !== null) {
            $salesShipmentEntity->fromArray($shipmentMethodTransfer->modifiedToArray());
        }

        $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
        if ($shipmentAddressTransfer !== null) {
            $salesShipmentEntity->setFkSalesOrderAddress($shipmentAddressTransfer->getIdSalesOrderAddress());
        }

        return $salesShipmentEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function mapShipmentEntityToShipmentTransfer(
        SpySalesShipment $salesShipmentEntity,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentTransfer {
        $shipmentTransfer->fromArray($salesShipmentEntity->toArray(), true);

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function mapShipmentEntityToShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        SpySalesShipment $salesShipment
    ): ShipmentMethodTransfer {
        return $shipmentMethodTransfer->fromArray($salesShipment->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function mapShipmentEntityToShipmentCarrierTransfer(
        ShipmentCarrierTransfer $shipmentCarrierTransfer,
        SpySalesShipment $salesShipment
    ): ShipmentCarrierTransfer {
        return $shipmentCarrierTransfer->setName($salesShipment->getCarrierName());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipment
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapShipmentEntityToShippingAddressTransfer(
        AddressTransfer $addressTransfer,
        SpySalesShipment $salesShipment
    ): AddressTransfer {
        $addressEntity = $salesShipment->getSpySalesOrderAddress();
        if ($addressEntity === null) {
            return $addressTransfer;
        }

        $addressTransfer->fromArray($addressEntity->toArray(), true);

        $countryTransfer = $this->mapCountryEntityToCountryTransfer($addressEntity->getCountry(), new CountryTransfer());

        $addressTransfer->setIso2Code($countryTransfer->getIso2Code());
        $addressTransfer->setCountry($countryTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function mapShipmentEntityToShipmentTransferWithDetails(
        SpySalesShipment $salesShipmentEntity,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentTransfer {
        $shipmentTransfer = $this->mapShipmentEntityToShipmentTransfer($salesShipmentEntity, $shipmentTransfer);
        $addressTransfer = $this->mapShipmentEntityToShippingAddressTransfer(new AddressTransfer(), $salesShipmentEntity);
        $methodTransfer = $this->mapShipmentEntityToShipmentMethodTransfer(new ShipmentMethodTransfer(), $salesShipmentEntity);
        $carrierTransfer = $this->mapShipmentEntityToShipmentCarrierTransfer(new ShipmentCarrierTransfer(), $salesShipmentEntity);

        if (!$this->isAddressEmpty($addressTransfer)) {
            $shipmentTransfer->setShippingAddress($addressTransfer);
        }

        $shipmentTransfer->setMethod($methodTransfer);
        $shipmentTransfer->setCarrier($carrierTransfer);

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $salesShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function mapShipmentMethodEntityToShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        SpyShipmentMethod $salesShipmentMethod
    ): ShipmentMethodTransfer {
        return $shipmentMethodTransfer->fromArray($salesShipmentMethod->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function mapShipmentTransferToShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentMethodTransfer {
        return $shipmentMethodTransfer->fromArray($shipmentTransfer->getMethod()->modifiedToArray(), true);
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountry $countryEntity
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function mapCountryEntityToCountryTransfer(
        SpyCountry $countryEntity,
        CountryTransfer $countryTransfer
    ): CountryTransfer {
        return $countryTransfer->fromArray($countryEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    protected function isAddressEmpty(AddressTransfer $addressTransfer): bool
    {
        foreach ($addressTransfer->toArray() as $addressValue) {
            if ($addressValue !== null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param iterable|\Orm\Zed\Sales\Persistence\SpySalesShipment[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderShipments
     * @param array $shipmentTransfers
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer[]
     */
    public function mapShipmentEntitiesToShipmentTransfers(iterable $salesOrderShipments, array $shipmentTransfers): array
    {
        foreach ($salesOrderShipments as $salesShipmentEntity) {
            $shipmentTransfers[] = $this
                ->mapShipmentEntityToShipmentTransferWithDetails($salesShipmentEntity, new ShipmentTransfer());
        }

        return $shipmentTransfers;
    }
}
