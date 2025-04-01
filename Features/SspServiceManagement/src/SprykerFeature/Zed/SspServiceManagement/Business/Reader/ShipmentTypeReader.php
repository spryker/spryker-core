<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Reader;

use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    /**
     * @param \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface $shipmentTypeFacade
     * @param \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig $sspServiceManagementConfig
     */
    public function __construct(
        protected ShipmentTypeFacadeInterface $shipmentTypeFacade,
        protected SspServiceManagementConfig $sspServiceManagementConfig
    ) {
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer|null
     */
    public function getDefaultShipmentType(string $storeName): ?ShipmentTypeTransfer
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->addKey($this->sspServiceManagementConfig->getDefaultShipmentType())
            ->addStoreName($storeName)
            ->setIsActive(true);

        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        if ($shipmentTypeCollectionTransfer->getShipmentTypes()->count() === 0) {
            return null;
        }

        return $shipmentTypeCollectionTransfer->getShipmentTypes()->offsetGet(0);
    }

    /**
     * @param array<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function getShipmentTypesIndexedByUuids(array $shipmentTypeUuids, string $storeName): array
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->setUuids($shipmentTypeUuids)
            ->addStoreName($storeName)
            ->setIsActive(true);

        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        $shipmentTypeTransfersIndexedByUuid = [];
        foreach ($shipmentTypeCollectionTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $shipmentTypeTransfersIndexedByUuid[$shipmentTypeTransfer->getUuidOrFail()] = $shipmentTypeTransfer;
        }

        return $shipmentTypeTransfersIndexedByUuid;
    }
}
