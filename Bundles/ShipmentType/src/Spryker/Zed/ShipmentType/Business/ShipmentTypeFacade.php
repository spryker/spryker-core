<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShipmentType\Business\ShipmentTypeBusinessFactory getFactory()
 * @method \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface getRepository()
 * @method \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface getEntityManager()
 */
class ShipmentTypeFacade extends AbstractFacade implements ShipmentTypeFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getShipmentTypeCollection(
        ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
    ): ShipmentTypeCollectionTransfer {
        return $this->getFactory()
            ->createShipmentTypeReader()
            ->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer
     */
    public function createShipmentTypeCollection(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): ShipmentTypeCollectionResponseTransfer {
        return $this->getFactory()
            ->createShipmentTypeCreator()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer
     */
    public function updateShipmentTypeCollection(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): ShipmentTypeCollectionResponseTransfer {
        return $this->getFactory()
            ->createShipmentTypeUpdater()
            ->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function expandShipmentMethodCollectionWithShipmentType(
        ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
    ): ShipmentMethodCollectionTransfer {
        return $this->getFactory()
            ->createShipmentMethodCollectionExpander()
            ->expandWithShipmentType($shipmentMethodCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function filterShipmentGroupMethods(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): ArrayObject
    {
        return $this->getFactory()
            ->createShipmentGroupFilter()
            ->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);
    }
}
