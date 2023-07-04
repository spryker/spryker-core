<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage\Plugin\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageClientInterface getClient()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Communication\ProductOfferShipmentTypeStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\ProductOfferShipmentTypeStorageFacadeInterface getFacade()
 */
class ShipmentTypeProductOfferStorageExpanderPlugin extends AbstractPlugin implements ProductOfferStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductOfferStorageTransfer.productOfferReference` to be set.
     * - Builds a key and retrieves product offer shipment type data from Storage by the key.
     * - Extracts shipment type UUIDs from product offer shipment type data.
     * - Uses `ShipmentTypeStorageClient::getShipmentTypeStorageCollection()` to retrieve shipment type data from Storage.
     * - Returns `ProductOfferStorageTransfer` expanded with shipment type storage data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expand(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer
    {
        return $this->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);
    }
}
