<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Communication\Plugin\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProductOfferStorageExtension\Dependency\Plugin\MerchantProductOfferPublishPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferStock\ProductOfferStockConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockFacadeInterface getFacade()
 */
class ProductOfferStockPublishPreCheckPlugin extends AbstractPlugin implements MerchantProductOfferPublishPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks product offer for availability in AvailabilityFacade
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return bool
     */
    public function isValid(ProductOfferTransfer $productOfferTransfer): bool
    {
        return true; // TODO: use $this->getFactory()->getAvailabilityFacade()
    }
}
