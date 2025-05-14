<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Sorter;

use SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig;

class ShipmentTypeGroupSorter implements ShipmentTypeGroupSorterInterface
{
    /**
     * @param \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig $sspServiceManagementConfig
     */
    public function __construct(protected SspServiceManagementConfig $sspServiceManagementConfig)
    {
    }

    /**
     * @param array<string, array<string, mixed>> $shipmentTypeGroups
     *
     * @return array<string, array<string, mixed>>
     */
    public function sortShipmentTypeGroups(array $shipmentTypeGroups): array
    {
        $shipmentTypeSortOrder = $this->sspServiceManagementConfig->getShipmentTypeSortOrder();
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
