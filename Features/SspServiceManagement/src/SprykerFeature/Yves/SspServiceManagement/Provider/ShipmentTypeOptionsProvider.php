<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Provider;

use SprykerFeature\Yves\SspServiceManagement\Sorter\ShipmentTypeGroupSorterInterface;
use SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig;

class ShipmentTypeOptionsProvider implements ShipmentTypeOptionsProviderInterface
{
    /**
     * @var string
     */
    protected const OPTION_LABEL = 'label';

    /**
     * @var string
     */
    protected const OPTION_VALUE = 'value';

    /**
     * @var string
     */
    protected const OPTION_IS_SERVICE_POINT_REQUIRED = 'isServicePointRequired';

    /**
     * @var string
     */
    protected const OPTION_SHIPMENT_TYPE_UUID = 'shipmentTypeUuid';

    /**
     * @var string
     */
    protected const OPTION_SERVICE_TYPE_KEY = 'serviceTypeKey';

    /**
     * @var string
     */
    protected const OPTION_SERVICE_TYPE_UUID = 'serviceTypeUuid';

    /**
     * @param \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig $sspServiceManagementConfig
     * @param \SprykerFeature\Yves\SspServiceManagement\Sorter\ShipmentTypeGroupSorterInterface $shipmentTypeGroupSorter
     */
    public function __construct(
        protected SspServiceManagementConfig $sspServiceManagementConfig,
        protected ShipmentTypeGroupSorterInterface $shipmentTypeGroupSorter
    ) {
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return array<int, array<string, mixed>>
     */
    public function provideShipmentTypeOptions(array $shipmentTypeStorageTransfers): array
    {
        $servicePointRequiredShipmentTypeKeys = $this->sspServiceManagementConfig->getServicePointRequiredShipmentTypeKeys();
        $isServicePointRequiredMap = array_combine($servicePointRequiredShipmentTypeKeys, $servicePointRequiredShipmentTypeKeys);

        $options = [];
        foreach ($shipmentTypeStorageTransfers as $shipmentTypeStorageTransfer) {
            $options[$shipmentTypeStorageTransfer->getKeyOrFail()] = [
                static::OPTION_LABEL => $shipmentTypeStorageTransfer->getNameOrFail(),
                static::OPTION_VALUE => $shipmentTypeStorageTransfer->getUuidOrFail(),
                static::OPTION_SHIPMENT_TYPE_UUID => $shipmentTypeStorageTransfer->getUuidOrFail(),
                static::OPTION_SERVICE_TYPE_KEY => $shipmentTypeStorageTransfer->getServiceType()?->getKey(),
                static::OPTION_SERVICE_TYPE_UUID => $shipmentTypeStorageTransfer->getServiceType()?->getUuid(),
                static::OPTION_IS_SERVICE_POINT_REQUIRED => $isServicePointRequiredMap[$shipmentTypeStorageTransfer->getKeyOrFail()] ?? false,
            ];
        }

        return array_values($this->shipmentTypeGroupSorter->sortShipmentTypeGroups($options));
    }
}
