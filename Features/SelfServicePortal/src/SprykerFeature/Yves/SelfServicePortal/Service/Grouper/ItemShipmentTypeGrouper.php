<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Grouper;

class ItemShipmentTypeGrouper extends AbstractShipmentTypeGrouper implements ItemShipmentTypeGrouperInterface
{
 /**
  * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
  *
  * @return array<string, array<string, list<\Generated\Shared\Transfer\ItemTransfer>>>
  */
    public function groupItemsByShipmentType(iterable $itemTransfers): array
    {
        $shipmentTypeGroups = [];

        foreach ($itemTransfers as $itemTransfer) {
            $shipmentTypeKey = $itemTransfer->getShipmentType()?->getKey() ?? $this->SelfServicePortalConfig::SHIPMENT_TYPE_DELIVERY;
            if (!isset($shipmentTypeGroups[$shipmentTypeKey])) {
                $shipmentTypeGroups[$shipmentTypeKey] = $this->createShipmentTypeGroup($shipmentTypeKey);
            }

            $shipmentTypeGroups[$shipmentTypeKey][static::SHIPMENT_TYPE_GROUP_ITEMS][] = $itemTransfer;
        }

        return $this->shipmentTypeGroupSorter->sortShipmentTypeGroups($shipmentTypeGroups);
    }
}
