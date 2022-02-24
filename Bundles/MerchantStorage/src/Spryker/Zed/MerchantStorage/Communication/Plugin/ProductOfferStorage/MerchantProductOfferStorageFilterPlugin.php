<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Communication\Plugin\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface;

/**
 * @method \Spryker\Zed\MerchantStorage\Communication\MerchantStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantStorage\Business\MerchantStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantStorage\MerchantStorageConfig getConfig()
 */
class MerchantProductOfferStorageFilterPlugin extends AbstractPlugin implements ProductOfferStorageFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters `ProductOfferCollection` transfer object by active and approved merchant.
     * - Returns `ProductOfferCollection` transfer object excluded product offers with no active or not approved merchant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function filterProductOfferStorages(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferCollectionTransfer {
        return $this->getFacade()->filterProductOfferStorages($productOfferCollectionTransfer);
    }
}
