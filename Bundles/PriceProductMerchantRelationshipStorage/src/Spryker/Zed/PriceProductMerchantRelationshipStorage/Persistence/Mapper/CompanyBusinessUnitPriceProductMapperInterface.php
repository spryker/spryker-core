<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper;

interface CompanyBusinessUnitPriceProductMapperInterface
{
    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[] $productStorePrices
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function mapProductAbstractPrices(array $productStorePrices): array;

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[] $productStorePrices
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function mapProductConcretePrices(array $productStorePrices): array;
}
