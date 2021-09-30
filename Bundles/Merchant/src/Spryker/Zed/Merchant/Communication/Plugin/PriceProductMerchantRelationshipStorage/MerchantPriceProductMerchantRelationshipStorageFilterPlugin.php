<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication\Plugin\PriceProductMerchantRelationshipStorage;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductMerchantRelationshipStorageExtension\Dependency\Plugin\PriceProductMerchantRelationshipStorageFilterPluginInterface;

/**
 * {@inheritDoc}
 *
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 * @method \Spryker\Zed\Merchant\Communication\MerchantCommunicationFactory getFactory()
 */
class MerchantPriceProductMerchantRelationshipStorageFilterPlugin extends AbstractPlugin implements PriceProductMerchantRelationshipStorageFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters `PriceProductMerchantRelationshipStorage` transfer objects by `Merchant.isActive` transfer property.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer> $priceProductMerchantRelationshipStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function filter(array $priceProductMerchantRelationshipStorageTransfers): array
    {
        return $this->getFacade()->filterPriceProductMerchantRelations($priceProductMerchantRelationshipStorageTransfers);
    }
}
