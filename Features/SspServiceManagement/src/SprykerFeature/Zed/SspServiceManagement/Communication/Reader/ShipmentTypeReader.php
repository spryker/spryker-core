<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Reader;

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
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer|null
     */
    public function findDefaultShipmentType(): ?ShipmentTypeTransfer
    {
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions(
                (new ShipmentTypeConditionsTransfer())
                    ->setKeys([$this->sspServiceManagementConfig->getDefaultShipmentType()])
                    ->setIsActive(true),
            );

        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        return $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current();
    }
}
