<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Provider;

interface ShipmentTypeOptionsProviderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return array<int, array<string, mixed>>
     */
    public function provideShipmentTypeOptions(array $shipmentTypeStorageTransfers): array;
}
