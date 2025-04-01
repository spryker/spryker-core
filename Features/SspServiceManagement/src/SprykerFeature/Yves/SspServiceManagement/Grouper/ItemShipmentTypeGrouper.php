<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Grouper;

use SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig;

class ItemShipmentTypeGrouper implements ItemShipmentTypeGrouperInterface
{
    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_GROUP_NAME = 'name';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_GROUP_ITEMS = 'items';

    /**
     * @var string
     */
    protected const DEFAULT_DELIVERY_NAME = 'Delivery';

    /**
     * @param \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig $sspServiceManagementConfig
     */
    public function __construct(protected SspServiceManagementConfig $sspServiceManagementConfig)
    {
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, array<string, list<\Generated\Shared\Transfer\ItemTransfer>>>
     */
    public function groupItemsByShipmentType(iterable $itemTransfers): array
    {
        $shipmentTypeGroups = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getShipmentType()) {
                $deliveryKey = $this->sspServiceManagementConfig::SHIPMENT_TYPE_DELIVERY;
                if (!isset($shipmentTypeGroups[$deliveryKey])) {
                    $shipmentTypeGroups[$deliveryKey] = $this->createShipmentTypeGroup(static::DEFAULT_DELIVERY_NAME);
                }

                $shipmentTypeGroups[$deliveryKey][static::SHIPMENT_TYPE_GROUP_ITEMS][] = $itemTransfer;

                continue;
            }

            $shipmentTypeKey = $itemTransfer->getShipmentTypeOrFail()->getKeyOrFail();
            $shipmentTypeName = $itemTransfer->getShipmentTypeOrFail()->getNameOrFail();

            if (!isset($shipmentTypeGroups[$shipmentTypeKey])) {
                $shipmentTypeGroups[$shipmentTypeKey] = $this->createShipmentTypeGroup($shipmentTypeName);
            }

            $shipmentTypeGroups[$shipmentTypeKey][static::SHIPMENT_TYPE_GROUP_ITEMS][] = $itemTransfer;
        }

        return $shipmentTypeGroups;
    }

    /**
     * @param string $shipmentTypeName
     *
     * @return array<string, mixed>
     */
    protected function createShipmentTypeGroup(string $shipmentTypeName): array
    {
        return [
            static::SHIPMENT_TYPE_GROUP_NAME => $shipmentTypeName,
            static::SHIPMENT_TYPE_GROUP_ITEMS => [],
        ];
    }
}
