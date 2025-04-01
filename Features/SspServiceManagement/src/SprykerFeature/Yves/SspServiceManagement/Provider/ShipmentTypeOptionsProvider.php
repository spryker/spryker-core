<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Provider;

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
     */
    public function __construct(protected SspServiceManagementConfig $sspServiceManagementConfig)
    {
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<int, array<string, mixed>>
     */
    public function provideShipmentTypeOptions(array $shipmentTypeTransfers): array
    {
        $servicePointRequiredShipmentTypeKeys = $this->sspServiceManagementConfig->getServicePointRequiredShipmentTypeKeys();
        $isServicePointRequiredMap = array_combine($servicePointRequiredShipmentTypeKeys, $servicePointRequiredShipmentTypeKeys);

        $options = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $options[] = [
                static::OPTION_LABEL => $shipmentTypeTransfer->getNameOrFail(),
                static::OPTION_VALUE => $shipmentTypeTransfer->getUuidOrFail(),
                static::OPTION_SHIPMENT_TYPE_UUID => $shipmentTypeTransfer->getUuidOrFail(),
                static::OPTION_SERVICE_TYPE_KEY => $shipmentTypeTransfer->getServiceType()?->getKey(),
                static::OPTION_SERVICE_TYPE_UUID => $shipmentTypeTransfer->getServiceType()?->getUuid(),
                static::OPTION_IS_SERVICE_POINT_REQUIRED => $isServicePointRequiredMap[$shipmentTypeTransfer->getKeyOrFail()] ?? false,
            ];
        }

        return $options;
    }
}
