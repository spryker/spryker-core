<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Business;

use Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePoint\Business\ShipmentTypeServicePointBusinessFactory getFactory()
 * @method \Spryker\Zed\ShipmentTypeServicePoint\Persistence\ShipmentTypeServicePointRepositoryInterface getRepository()
 */
class ShipmentTypeServicePointFacade extends AbstractFacade implements ShipmentTypeServicePointFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer
     */
    public function getShipmentTypeServiceTypeCollection(
        ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
    ): ShipmentTypeServiceTypeCollectionTransfer {
        return $this->getFactory()
            ->createShipmentTypeServiceTypeReader()
            ->getShipmentTypeServiceTypeCollection($shipmentTypeServiceTypeCriteriaTransfer);
    }
}
