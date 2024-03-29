<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\ProductOfferServicePointStorage;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferServicePointStorageExtension\Dependeency\Plugin\ProductOfferServiceStorageFilterPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\ProductOfferServicePointStorage\MerchantProductOfferServiceCollectionStorageFilterPlugin} instead.
 *
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Communication\MerchantProductOfferStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageFacadeInterface getFacade()
 */
class MerchantProductOfferServiceStorageFilterPlugin extends AbstractPlugin implements ProductOfferServiceStorageFilterPluginInterface
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
