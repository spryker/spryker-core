<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig getSharedConfig()
 */
class PriceProductMerchantRelationshipStorageConfig extends AbstractBundleConfig
{
    public const PRICE_PRODUCT_MERCHANT_RELATIONSHIP_SYNC_STORAGE_QUEUE = 'sync.storage.price';

    /**
     * @return string
     */
    public function getPriceDimensionMerchantRelationship()
    {
        return $this->getSharedConfig()->getPriceDimensionMerchantRelationship();
    }

    /**
     * @return string|null
     */
    public function getPriceProductConcreteMerchantRelationSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getPriceProductAbstractMerchantRelationSynchronizationPoolName(): ?string
    {
        return null;
    }
}
