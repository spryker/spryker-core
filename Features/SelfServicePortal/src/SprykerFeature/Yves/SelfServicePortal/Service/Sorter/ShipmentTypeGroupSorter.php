<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Sorter;

use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class ShipmentTypeGroupSorter implements ShipmentTypeGroupSorterInterface
{
    /**
     * @param \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     */
    public function __construct(protected SelfServicePortalConfig $selfServicePortalConfig)
    {
    }

    /**
     * @param array<string, array<string, mixed>> $shipmentTypeGroups
     *
     * @return array<string, array<string, mixed>>
     */
    public function sortShipmentTypeGroups(array $shipmentTypeGroups): array
    {
        $shipmentTypeSortOrder = $this->selfServicePortalConfig->getShipmentTypeSortOrder();
        $sortedShipmentTypeGroups = [];

        foreach ($shipmentTypeSortOrder as $shipmentTypeKey) {
            if (isset($shipmentTypeGroups[$shipmentTypeKey])) {
                $sortedShipmentTypeGroups[$shipmentTypeKey] = $shipmentTypeGroups[$shipmentTypeKey];
                unset($shipmentTypeGroups[$shipmentTypeKey]);
            }
        }

        if (count($shipmentTypeGroups) > 0) {
            $sortedShipmentTypeGroups += $shipmentTypeGroups;
        }

        return $sortedShipmentTypeGroups;
    }
}
