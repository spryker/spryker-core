<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\ProductOfferShipmentTypeStorage;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Communication\MerchantProductOfferStorageCommunicationFactory getFactory()
 */
class MerchantProductOfferShipmentTypeStorageFilterPlugin extends AbstractPlugin implements ProductOfferShipmentTypeStorageFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductOfferShipmentTypeCollectionTransfer.productOfferShipmentTypes.productOffer` to be set.
     * - Filters out `ProductOfferShipmentTypeCollectionTransfer.productOfferShipmentTypes` with product offers with inactive merchants.
     * - Doesn't filter out product offers without merchant references.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function filter(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        return $this->getFacade()->filterProductOfferShipmentTypeCollection($productOfferShipmentTypeCollectionTransfer);
    }
}
