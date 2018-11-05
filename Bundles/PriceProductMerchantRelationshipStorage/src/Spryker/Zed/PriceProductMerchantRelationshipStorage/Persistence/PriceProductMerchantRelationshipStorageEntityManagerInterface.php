<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;

interface PriceProductMerchantRelationshipStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function updatePriceProductAbstract(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
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
     * @param string[] $priceKeys
     *
     * @return void
     */
    public function deletePriceProductAbstractsByPriceKeys(
        array $priceKeys
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function updatePriceProductConcrete(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
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
     * @param string[] $priceKeys
     *
     * @return void
     */
    public function deletePriceProductConcretesByPriceKeys(
        array $priceKeys
    ): void;
}
