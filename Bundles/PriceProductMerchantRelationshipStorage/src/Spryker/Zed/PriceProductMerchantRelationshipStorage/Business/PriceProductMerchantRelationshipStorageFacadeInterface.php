<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageBusinessFactory getFactory()
 */
interface PriceProductMerchantRelationshipStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishConcretePriceProductByBusinessUnitProducts(array $businessUnitProducts): void;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishAbstractPriceProductByBusinessUnitProducts(array $businessUnitProducts): void;
}
