<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage;

interface PriceProductMerchantRelationshipStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage $priceProductAbstractMerchantRelationshipStorageEntity
     *
     * @return void
     */
    public function updatePriceProductAbstract(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer,
        SpyPriceProductAbstractMerchantRelationshipStorage $priceProductAbstractMerchantRelationshipStorageEntity
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function createPriceProductAbstract(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void;

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[] $priceProductAbstractMerchantRelationshipStorageEntities
     *
     * @return void
     */
    public function deletePriceProductAbstractEntities(
        array $priceProductAbstractMerchantRelationshipStorageEntities
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage $priceProductConcreteMerchantRelationshipStorageEntity
     *
     * @return void
     */
    public function updatePriceProductConcrete(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer,
        SpyPriceProductConcreteMerchantRelationshipStorage $priceProductConcreteMerchantRelationshipStorageEntity
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function createPriceProductConcrete(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void;

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[] $priceProductConcreteMerchantRelationshipStorageEntities
     *
     * @return void
     */
    public function deletePriceProductConcreteEntities(
        array $priceProductConcreteMerchantRelationshipStorageEntities
    ): void;
}
