<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

interface PriceProductMerchantRelationshipStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransferCollection
     * @param array $existingPriceProductMerchantRelationshipStorageEntityMap
     *
     * @return void
     */
    public function writePriceProductConcrete(
        array $priceProductMerchantRelationshipStorageTransferCollection,
        array $existingPriceProductMerchantRelationshipStorageEntityMap
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransferCollection
     * @param array $existingPriceProductMerchantRelationshipStorageEntityMap
     *
     * @return void
     */
    public function writePriceProductAbstract(
        array $priceProductMerchantRelationshipStorageTransferCollection,
        array $existingPriceProductMerchantRelationshipStorageEntityMap
    ): void;

    /**
     * @param int $idCompanyBusinessUnit
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deletePriceProductAbstractByCompanyBusinessUnitAndIdProductAbstract(
        int $idCompanyBusinessUnit,
        int $idProductAbstract
    ): void;

    /**
     * @param int $idCompanyBusinessUnit
     * @param int $idProduct
     *
     * @return void
     */
    public function deletePriceProductConcreteByCompanyBusinessUnitAndIdProduct(
        int $idCompanyBusinessUnit,
        int $idProduct
    ): void;
}
