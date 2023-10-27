<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\ProductOfferServicePointStorage;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferServicePointStorageExtension\Dependency\Plugin\ProductOfferServiceCollectionStorageFilterPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Communication\MerchantProductOfferStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageFacadeInterface getFacade()
 */
class MerchantProductOfferServiceCollectionStorageFilterPlugin extends AbstractPlugin implements ProductOfferServiceCollectionStorageFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters product offer services collection by active and approved merchants.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>
     */
    public function filterProductOfferServices(array $productOfferServicesTransfers): array
    {
        return $this->getFacade()->filterProductOfferServices($productOfferServicesTransfers);
    }
}
