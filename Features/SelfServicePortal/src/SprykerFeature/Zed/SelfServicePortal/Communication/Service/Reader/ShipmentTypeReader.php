<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader;

use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    public function __construct(
        protected ShipmentTypeFacadeInterface $shipmentTypeFacade,
        protected SelfServicePortalConfig $SelfServicePortalConfig
    ) {
    }

    public function findDefaultShipmentType(): ?ShipmentTypeTransfer
    {
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions(
                (new ShipmentTypeConditionsTransfer())
                    ->setKeys([$this->SelfServicePortalConfig->getDefaultShipmentType()])
                    ->setIsActive(true),
            );

        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        return $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current();
    }
}
