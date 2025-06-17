<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Grouper;

use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Yves\SelfServicePortal\Service\Sorter\ShipmentTypeGroupSorterInterface;

abstract class AbstractShipmentTypeGrouper
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
     * @param \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig $SelfServicePortalConfig
     * @param \SprykerFeature\Yves\SelfServicePortal\Service\Sorter\ShipmentTypeGroupSorterInterface $shipmentTypeGroupSorter
     */
    public function __construct(
        protected SelfServicePortalConfig $SelfServicePortalConfig,
        protected ShipmentTypeGroupSorterInterface $shipmentTypeGroupSorter
    ) {
    }

    /**
     * @param string $shipmentTypeKey
     *
     * @return array<string, mixed>
     */
    protected function createShipmentTypeGroup(string $shipmentTypeKey): array
    {
        return [
            static::SHIPMENT_TYPE_GROUP_NAME => $shipmentTypeKey,
            static::SHIPMENT_TYPE_GROUP_ITEMS => [],
        ];
    }
}
