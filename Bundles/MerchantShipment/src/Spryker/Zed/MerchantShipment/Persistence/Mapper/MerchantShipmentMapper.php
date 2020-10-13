<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Persistence\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;

class MerchantShipmentMapper
{
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
        $addressTransfer = $this->mapShipmentEntityToShippingAddressTransfer(new AddressTransfer(), $salesShipmentEntity);
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())->fromArray($salesShipmentEntity->toArray(), true);
        $shipmentCarrierTransfer = (new ShipmentCarrierTransfer())->setName($salesShipmentEntity->getCarrierName());

        if (!$this->isAddressEmpty($addressTransfer)) {
            $shipmentTransfer->setShippingAddress($addressTransfer);
        }

        $shipmentTransfer->setMethod($shipmentMethodTransfer);
        $shipmentTransfer->setCarrier($shipmentCarrierTransfer);

        return $shipmentTransfer;
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
        if (!$salesShipment->getSpySalesOrderAddress()) {
            return $addressTransfer;
        }

        $addressTransfer->fromArray($salesShipment->getSpySalesOrderAddress()->toArray(), true);
        $countryTransfer = (new CountryTransfer())->fromArray(
            $salesShipment->getSpySalesOrderAddress()->getCountry()->toArray(),
            true
        );

        $addressTransfer->setIso2Code($countryTransfer->getIso2Code());
        $addressTransfer->setCountry($countryTransfer);

        return $addressTransfer;
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
}
