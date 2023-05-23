<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;

class ShipmentTypesBackendApiToShipmentTypeFacadeBridge implements ShipmentTypesBackendApiToShipmentTypeFacadeInterface
{
    /**
     * @var \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface
     */
    protected $shipmentTypeFacade;

    /**
     * @param \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface $shipmentTypeFacade
     */
    public function __construct($shipmentTypeFacade)
    {
        $this->shipmentTypeFacade = $shipmentTypeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getShipmentTypeCollection(
        ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
    ): ShipmentTypeCollectionTransfer {
        return $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer
     */
    public function createShipmentTypeCollection(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): ShipmentTypeCollectionResponseTransfer {
        return $this->shipmentTypeFacade->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer
     */
    public function updateShipmentTypeCollection(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): ShipmentTypeCollectionResponseTransfer {
        return $this->shipmentTypeFacade->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
    }
}
