<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageMapperPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Communication\MerchantProductOfferStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageFacadeInterface getFacade()
 */
class MerchantProductOfferStorageMapperPlugin extends AbstractPlugin implements ProductOfferStorageMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `ProductOfferTransfer.fkMerchant` to `ProductOfferStorageTransfer.idMerchant`.
     * - Maps `ProductOfferTransfer.merchantSku` to `ProductOfferStorageTransfer.merchantSku`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function map(
        ProductOfferTransfer $productOfferTransfer,
        ProductOfferStorageTransfer $productOfferStorageTransfer
    ): ProductOfferStorageTransfer {
        $productOfferStorageTransfer->setIdMerchant($productOfferTransfer->getFkMerchant());
        $productOfferStorageTransfer->setMerchantSku($productOfferTransfer->getMerchantSku());

        return $productOfferStorageTransfer;
    }
}
