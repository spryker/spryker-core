<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage\Plugin\ShipmentTypeStorage;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin\AvailableShipmentTypeFilterPluginInterface;

/**
 * @method \Spryker\Client\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageClientInterface getClient()
 */
class ShipmentTypeProductOfferAvailableShipmentTypeFilterPlugin extends AbstractPlugin implements AvailableShipmentTypeFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Collects product offer SKUs from `QuoteTransfer.items`.
     * - Retrieves product offers by SKUs from storage.
     * - Filters out shipment types without product offer shipment type relation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function filter(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        QuoteTransfer $quoteTransfer,
    ): ShipmentTypeStorageCollectionTransfer {
        return $this->getClient()->filterUnavailableProductOfferShipmentTypes(
            $shipmentTypeStorageCollectionTransfer,
            $quoteTransfer,
        );
    }
}
