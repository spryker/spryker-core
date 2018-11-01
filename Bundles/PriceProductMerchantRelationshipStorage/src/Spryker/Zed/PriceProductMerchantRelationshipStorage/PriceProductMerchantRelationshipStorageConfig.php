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
     * @uses \Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT
     */
    public const COL_FK_COMPANY_BUSINESS_UNIT = 'spy_merchant_relationship_to_company_business_unit.fk_company_business_unit';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    public const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    public const PRICE_MODE_NET = 'NET_MODE';

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

    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }
}
