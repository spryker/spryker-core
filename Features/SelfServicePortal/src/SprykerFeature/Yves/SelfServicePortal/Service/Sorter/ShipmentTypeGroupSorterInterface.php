<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Sorter;

interface ShipmentTypeGroupSorterInterface
{
    /**
     * @param array<string, array<string, mixed>> $shipmentTypeGroups
     *
     * @return array<string, array<string, mixed>>
     */
    public function sortShipmentTypeGroups(array $shipmentTypeGroups): array;
}
